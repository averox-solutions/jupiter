<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Http\Requests\ProcessCheckoutRequest;
use App\Models\Plan;
use App\Models\TaxRate;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    use PaymentTrait;

    private $defaultInterval = 'month';

    private $intervals = ['month', 'year'];

    // Display the checkout form.
    public function index(Request $request, $id)
    {
        if (Auth::user()->role == 'admin') return redirect()->route('home');

        if (count(paymentGateways()) == 0) {
            return redirect()->route('home');
        }
        $request->session()->forget(['plan_redirect']);

        // If no interval is set
        if (!in_array($request->input('interval'), $this->intervals)) {
            // Redirect to a default interval
            return redirect()->route('checkout.index', ['id' => $id, 'interval' => $this->defaultInterval]);
        }

        $plan = Plan::where('id', '=', $id)->where('status', 1)->priced()->firstOrFail();

        // If the user is already subscribed to the plan
        if ($request->user()->plan->id == $plan->id) {
            return redirect()->route('pricing');
        }

        $coupon = null;

        // If the plan has coupons assigned
        if ($plan->coupons) {
            // If a coupon was set
            if ($request->old('coupon')) {
                $coupon = Coupon::where('code', '=', $request->old('coupon'))->where('status', 1)->first() ?? null;

                // If the coupon isn't available on this plan
                if ($coupon != null) {
                    if (!in_array($coupon->id, $plan->coupons) || $coupon->quantity <= $coupon->redeems) {
                        $coupon = null;
                    }
                }
            }
        }

        // Get the tax rates
        $taxRates = TaxRate::whereIn('id', $plan->tax_rates ?? [])->ofRegion(old('country') ?? ($request->user()->billing_information->country ?? null))->orderBy('type')->where('status', 1)->get();

        // Sum the inclusive tax rates
        $inclTaxRatesPercentage = $taxRates->where('type', '=', 0)->where('status', 1)->sum('percentage');

        // Sum the exclusive tax rates
        $exclTaxRatesPercentage = $taxRates->where('type', '=', 1)->where('status', 1)->sum('percentage');

        return view('checkout.index', ['page' => __('Checkout'), 'plan' => $plan, 'user' => $request->user(), 'taxRates' => $taxRates, 'coupon' => $coupon, 'inclTaxRatesPercentage' => $inclTaxRatesPercentage, 'exclTaxRatesPercentage' => $exclTaxRatesPercentage]);
    }

    /**
     * Process the payment request.
     */
    public function process(ProcessCheckoutRequest $request, $id)
    {
        if (Auth::user()->role == 'admin') return redirect();
        $plan = Plan::where('id', '=', $id)->where('status', 1)->priced()->firstOrFail();

        // If the user is already subscribed to the plan
        if ($request->user()->plan->id == $plan->id) {
            return redirect()->route('pricing');
        }

        // If the user wants to skip trial

        // If the user's country has changed, or a coupon was applied
        if ($request->has('country') && !$request->has('submit') || $request->has('coupon') && !$request->has('coupon_set')) {
            return redirect()->back()->withInput();
        }

        // Update the user's billing information
        $request->user()->billing_information = [
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'postal_code' => $request->input('postal_code'),
            'state' => $request->input('state'),
            'address' => $request->input('address'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone')
        ];

        $request->user()->save();

        // Get the coupon
        $coupon = $plan->coupons && $request->input('coupon') ? Coupon::where('code', '=', $request->input('coupon'))->where('status', 1)->firstOrFail() : null;

        // Get the tax rates
        $taxRates = TaxRate::whereIn('id', $plan->tax_rates ?? [])->ofRegion($request->country ?? null)->orderBy('type')->where('status', 1)->get();

        // Sum the inclusive tax rates
        $inclTaxRatesPercentage = $taxRates->where('type', '=', 0)->where('status', 1)->sum('percentage');

        // Sum the exclusive tax rates
        $exclTaxRatesPercentage = $taxRates->where('type', '=', 1)->where('status', 1)->sum('percentage');

        // Get the total amount to be charged
        $amount = str_replace(',', '', formatMoney(checkoutTotal(($request->input('interval') == 'year' ? $plan->amount_year : $plan->amount_month), $coupon->percentage ?? null, $exclTaxRatesPercentage, $inclTaxRatesPercentage), $plan->currency));

        $taxRates = TaxRate::whereIn('id', $plan->tax_rates ?? [])->ofRegion($request->country ?? null)->where('status', 1)->orderBy('type')->get();

        // If a redeemable coupon was used
        if ($coupon && $coupon->type == 1) {
            return $this->redeemPlan($request, $plan, $coupon);
        } elseif ($request->input('payment_gateway') == 'STRIPE') {
            return $this->initStripe($request, $plan, $coupon, $taxRates, $amount);
        } elseif ($request->input('payment_gateway') == 'PAYPAL') {
            return $this->initPayPal($request, $plan, $coupon, $taxRates, $amount);
        }
    }

    /**
     * Redeem a plan.
     */
    private function redeemPlan(Request $request, Plan $plan, Coupon $coupon)
    {
        if (Auth::user()->role == 'admin') return redirect();
        // Cancel the current plan
        $request->user()->planSubscriptionCancel();

        // Store the new plan
        $request->user()->plan_id = $plan->id;
        $request->user()->plan_amount = null;
        $request->user()->plan_currency = null;
        $request->user()->plan_interval = null;
        $request->user()->plan_payment_gateway = null;
        $request->user()->plan_subscription_id = null;
        $request->user()->plan_subscription_status = null;
        $request->user()->plan_recurring_at = null;
        $request->user()->plan_ends_at = $coupon->days < 0 ? null : Carbon::now()->addDays($coupon->days);
        $request->user()->save();

        // Increase the coupon usage
        $coupon->increment('redeems', 1);

        return redirect()->route('checkout.complete');
    }

    /**
     * Initialize the Stripe payment.
     */
    private function initStripe(Request $request, Plan $plan, $coupon, $taxRates, $amount)
    {
        $stripe = new \Stripe\StripeClient(
            getSetting('STRIPE_SECRET')
        );
        // Attempt to retrieve the product
        try {
            $stripeProduct = $stripe->products->retrieve($plan->id);

            // Check if the plan's name has changed
            if ($plan->name != $stripeProduct->name) {

                // Attempt to update the product
                try {
                    $stripeProduct = $stripe->products->update($stripeProduct->id, [
                        'name' => $plan->name
                    ]);
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            // Attempt to create the product
            try {
                $stripeProduct = $stripe->products->create([
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'description' => $plan->description
                ]);
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        $stripeAmount = in_array($plan->currency, config('currencies.zero_decimals')) ? (float)$amount : ((float)$amount * 100);

        $stripePlan = $plan->id . '_' . $request->input('interval') . '_' . $stripeAmount . '_' . $plan->currency;

        // Attempt to retrieve the plan
        try {
            $stripePlan = $stripe->plans->retrieve($stripePlan);
        } catch (\Exception $e) {
            // Attempt to create the plan
            try {
                $stripePlan = $stripe->plans->create([
                    'amount' => $stripeAmount,
                    'currency' => $plan->currency,
                    'interval' => $request->input('interval'),
                    'product' => $stripeProduct->id,
                    'id' => $stripePlan,
                ]);
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        // Attempt to create the checkout session
        try {
            $stripeSession = $stripe->checkout->sessions->create([
                'success_url' => route('checkout.complete'),
                'cancel_url' => route('checkout.cancelled'),
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price' => $stripePlan->id,
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'subscription_data' => [
                    'metadata' => [
                        'user' => $request->user()->id,
                        'plan' => $plan->id,
                        'plan_amount' => $request->input('interval') == 'year' ? $plan->amount_year : $plan->amount_month,
                        'amount' => $amount,
                        'currency' => $plan->currency,
                        'interval' => $request->input('interval'),
                        'coupon' => $coupon->id ?? null,
                        'tax_rates' => $taxRates->pluck('id')->implode('_')
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return view('checkout.gateways.stripe', ['stripeSession' => $stripeSession]);
    }

    // Initialize the PayPal payment.
    private function initPayPal(Request $request, Plan $plan, $coupon, $taxRates, $amount)
    {
        $httpClient = new HttpClient(['verify' => false]);

        $httpBaseUrl = 'https://' . (getSetting('PAYPAL_MODE') == 'sandbox' ? 'api-m.sandbox' : 'api-m') . '.paypal.com/';

        // Attempt to retrieve the auth token
        try {
            $payPalAuthRequest = $httpClient->request(
                'POST',
                $httpBaseUrl . 'v1/oauth2/token',
                [
                    'auth' => [getSetting('PAYPAL_CLIENT_ID'), getSetting('PAYPAL_SECRET')],
                    'form_params' => [
                        'grant_type' => 'client_credentials'
                    ]
                ]
            );

            $payPalAuth = json_decode($payPalAuthRequest->getBody()->getContents());
        } catch (BadResponseException $e) {
            return back()->with('error', $e->getResponse()->getBody()->getContents());
        }

        $payPalProduct = 'product_' . $plan->id;

        // Attempt to retrieve the product
        try {
            $payPalProductRequest = $httpClient->request(
                'GET',
                $httpBaseUrl . 'v1/catalogs/products/' . $payPalProduct,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $payPalAuth->access_token,
                        'Content-Type' => 'application/json'
                    ]
                ]
            );

            $payPalProduct = json_decode($payPalProductRequest->getBody()->getContents());
        } catch (\Exception $e) {
            // Attempt to create the product
            try {
                $payPalProductRequest = $httpClient->request(
                    'POST',
                    $httpBaseUrl . 'v1/catalogs/products',
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $payPalAuth->access_token,
                            'Content-Type' => 'application/json'
                        ],
                        'body' => json_encode([
                            'id' => $payPalProduct,
                            'name' => $plan->name,
                            'description' => $plan->description,
                            'type' => 'SERVICE'
                        ])
                    ]
                );

                $payPalProduct = json_decode($payPalProductRequest->getBody()->getContents());
            } catch (BadResponseException $e) {
                return back()->with('error', $e->getResponse()->getBody()->getContents());
            }
        }

        $payPalAmount = $amount;

        $payPalPlan = 'plan_' . $plan->id . '_' . $request->input('interval') . '_' . $payPalAmount . '_' . $plan->currency;

        // Attempt to create the plan
        try {
            $payPalPlanRequest = $httpClient->request(
                'POST',
                $httpBaseUrl . 'v1/billing/plans',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $payPalAuth->access_token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        'product_id' => $payPalProduct->id,
                        'name' => $payPalPlan,
                        'status' => 'ACTIVE',
                        'billing_cycles' => [
                            [
                                'frequency' => [
                                    'interval_unit' => strtoupper($request->input('interval')),
                                    'interval_count' => 1,
                                ],
                                'tenure_type' => 'REGULAR',
                                'sequence' => 1,
                                'total_cycles' => 0,
                                'pricing_scheme' => [
                                    'fixed_price' => [
                                        'value' => $payPalAmount,
                                        'currency_code' => $plan->currency,
                                    ],
                                ]
                            ]
                        ],
                        'payment_preferences' => [
                            'auto_bill_outstanding' => true,
                            'payment_failure_threshold' => 0,
                        ],
                    ])
                ]
            );

            $payPalPlan = json_decode($payPalPlanRequest->getBody()->getContents());
        } catch (BadResponseException $e) {
            return back()->with('error', $e->getResponse()->getBody()->getContents());
        }

        // Attempt to create the subscription
        try {
            $payPalSubscriptionRequest = $httpClient->request(
                'POST',
                $httpBaseUrl . 'v1/billing/subscriptions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $payPalAuth->access_token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        'plan_id' => $payPalPlan->id,
                        'application_context' => [
                            'brand_name' => getSetting('APPLICATION_NAME'),
                            // 'brand_name' => '',
                            'locale' => 'en-US',
                            'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                            'user_action' => 'SUBSCRIBE_NOW',
                            'payment_method' => [
                                'payer_selected' => 'PAYPAL',
                                'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                            ],
                            'return_url' => route('checkout.complete'),
                            'cancel_url' => route('checkout.cancelled')
                        ],
                        'custom_id' => http_build_query([
                            'user' => $request->user()->id,
                            'plan' => $plan->id,
                            'plan_amount' => $request->input('interval') == 'year' ? $plan->amount_year : $plan->amount_month,
                            'amount' => $amount,
                            'currency' => $plan->currency,
                            'interval' => $request->input('interval'),
                            'coupon' => $coupon->id ?? null,
                            'tax_rates' => $taxRates->pluck('id')->implode('_'),
                        ])
                    ])
                ]
            );

            $payPalSubscription = json_decode($payPalSubscriptionRequest->getBody()->getContents());
        } catch (BadResponseException $e) {
            return back()->with('error', $e->getResponse()->getBody()->getContents());
        }

        return redirect($payPalSubscription->links[0]->href);
    }

    /**
     * Display the Payment complete page.
     */
    public function complete()
    {
        return view('checkout.complete', ['page' => __('Completed')]);
    }

    /**
     * Display the Payment pending page.
     */
    public function pending()
    {
        return view('checkout.pending', ['page' => __('Pending')]);
    }

    /**
     * Display the Payment cancelled page.
     */
    public function cancelled()
    {
        return view('checkout.cancelled', ['page' => __('Cancelled')]);
    }
}
