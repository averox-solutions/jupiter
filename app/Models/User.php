<?php

namespace App\Models;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorAuthMail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Determine if the user is on the default plan subscription.
     */
    public function planOnDefault()
    {
        return $this->plan_id == 1;
    }

    /**
     * Determine if the plan subscription is within its grace period after cancellation.
     */
    public function planOnGracePeriod()
    {
        return $this->plan_ends_at;
    }

    /**
     * Determine if the plan subscription is active.
     */
    public function planActive()
    {
        if ($this->plan_payment_gateway == 'paypal') {
            return $this->planOnTrial() || $this->planOnGracePeriod() || $this->plan_subscription_status == 'ACTIVE';
        } elseif ($this->plan_payment_gateway == 'stripe') {
            return $this->planOnTrial() || $this->planOnGracePeriod() || $this->plan_subscription_status == 'active';
        } else {
            return !$this->planCancelled() || $this->planOnTrial() || $this->planOnGracePeriod();
        }
    }

    /**
     * Determine if the plan subscription is within its trial period.
     */
    public function planOnTrial()
    {
        return $this->plan_trial_ends_at;
    }

    /**
     * Determine if the plan subscription is no longer active.
     */
    public function planCancelled()
    {
        return !is_null($this->plan_ends_at);
    }
    
    /**
     * Get the plan that the user owns.
     */
    public function plan()
    {
        // If the current plan is default, or the plan is not active
        if ($this->planOnDefault() || !$this->planActive()) {

            // Switch to the default plan
            $this->plan_id = 1;
        }

        return $this->belongsTo('App\Models\Plan');
    }

    /**
     * Cancel the current plan.
     */
    public function planSubscriptionCancel()
    {
        if ($this->plan_payment_gateway == 'paypal') {
            $httpClient = new HttpClient(['verify' => false]);

            $httpBaseUrl = 'https://'.(getSetting('PAYPAL_MODE') == 'sandbox' ? 'api-m.sandbox' : 'api-m').'.paypal.com/';

            // Attempt to retrieve the auth token
            try {
                $payPalAuthRequest = $httpClient->request('POST', $httpBaseUrl . 'v1/oauth2/token', [
                        'auth' => [getSetting('PAYPAL_CLIENT_ID'), getSetting('PAYPAL_SECRET')],
                        'form_params' => [
                            'grant_type' => 'client_credentials'
                        ]
                    ]
                );

                $payPalAuth = json_decode($payPalAuthRequest->getBody()->getContents());
            } catch (BadResponseException $e) {}

            // Attempt to cancel the subscription
            try {
                $payPalSubscriptionCancelRequest = $httpClient->request('POST', $httpBaseUrl . 'v1/billing/subscriptions/' . $this->plan_subscription_id . '/cancel', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $payPalAuth->access_token,
                            'Content-Type' => 'application/json'
                        ],
                        'body' => json_encode([
                            'reason' => __('Cancelled')
                        ])
                    ]
                );
            } catch (BadResponseException $e) {}
        } elseif ($this->plan_payment_gateway == 'stripe') {
            $stripe = new \Stripe\StripeClient(
                getSetting('STRIPE_SECRET')
            );

            // Attempt to cancel the current subscription
            try {
                $stripe->subscriptions->update(
                    $this->plan_subscription_id,
                    ['cancel_at_period_end' => true]
                );
            } catch (\Exception $e) {}
        }

        $this->plan_ends_at = $this->plan_recurring_at;
        $this->plan_payment_gateway = null;
        $this->plan_recurring_at = null;
        $this->save();
    }

    //user meeting relation
    public function meeting() {
        return $this->hasMany('App\Models\Meeting');
    }

    //use contacts relation
    public function contact() {
        return $this->hasMany('App\Models\Contact');
    }

    //generate and send TFA code
    public function generateCode()
    {
       $code = rand(1000, 9999);

       UserCode::updateOrCreate(
           [ 'user_id' => auth()->user()->id ],
           [ 'code' => $code ]
       );

       try {
           $details = [
               'code'  => $code
           ];

           Mail::to(auth()->user()->email)->send(new TwoFactorAuthMail($details));
       } catch (\Exception $e) {
           info("Error: ". $e->getMessage());
       }
    }
}
