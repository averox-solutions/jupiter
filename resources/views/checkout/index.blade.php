@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container py-3 my-3">
            @include('include.message')
            <form method="post" id="form-payment">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div
                            class="card border-0 shadow-sm mb-3 overflow-hidden @if ($coupon && $coupon->type) d-none @endif">
                            <div class="card-header">
                                <div class="font-weight-medium py-1">{{ __('Payment method') }}</div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach (paymentGateways() as $key => $value)
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="payment-method-{{ $key }}"
                                                    name="payment_gateway"
                                                    class="custom-control-input{{ $errors->has('payment_gateway') ? ' is-invalid' : '' }}"
                                                    value="{{ $key }}"
                                                    @if (
                                                        (request()->input('payment') == $key && old('payment_gateway') == null) ||
                                                            old('payment_gateway') == $key ||
                                                            ($loop->first && old('payment_gateway') == null)) ) checked @endif>
                                                <label class="custom-control-label cursor-pointer d-block"
                                                    for="payment-method-{{ $key }}">
                                                    <div class="row">
                                                        <div class="col-12 col-lg d-flex align-items-center">
                                                            {{ $value['key'] }}</div>
                                                        <div class="col-12 col-lg-auto mt-1 mt-lg-0">
                                                            <div class="text-muted">{{ __($value['type']) }}</div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if ($errors->has('payment_gateway'))
                                <div class="card-footer">
                                    <span class="invalid-feedback d-block mt-0" role="alert">
                                        <strong>{{ $errors->first('payment_gateway') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <div class="font-weight-medium py-1">{{ __('Billing information') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="i-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="i-name"
                                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        value="{{ old('name') ?? ($user->billing_information->name ?? null) }}"
                                        placeholder="{{ __('Name') }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="i-address">{{ __('Address') }}</label>
                                    <input type="text" name="address" id="i-address"
                                        class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                        value="{{ old('address') ?? ($user->billing_information->address ?? null) }}"
                                        placeholder="{{ __('Address') }}">
                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="i-city">{{ __('City') }}</label>
                                            <input type="text" name="city" id="i-city"
                                                class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                                                value="{{ old('city') ?? ($user->billing_information->city ?? null) }}"
                                                placeholder="{{ __('City') }}">
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="i-state">{{ __('State') }}</label>
                                            <input type="text" name="state" id="i-state"
                                                class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}"
                                                value="{{ old('state') ?? ($user->billing_information->state ?? null) }}"
                                                placeholder="{{ __('State') }}">
                                            @if ($errors->has('state'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="i-postal-code">{{ __('Postal code') }}</label>
                                            <input type="text" name="postal_code" id="i-postal-code"
                                                class="form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}"
                                                value="{{ old('postal_code') ?? ($user->billing_information->postal_code ?? null) }}"
                                                placeholder="{{ __('Postal code') }}">
                                            @if ($errors->has('postal_code'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('postal_code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="i-country">{{ __('Country') }}</label>
                                    <select name="country" id="i-country"
                                        class="custom-select{{ $errors->has('country') ? ' is-invalid' : '' }}">
                                        <option value="" hidden disabled selected>{{ __('Country') }}</option>
                                        @foreach (config('countries') as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (
                                                    (old('country') !== null && $key == old('country')) ||
                                                        (isset($user->billing_information->country) &&
                                                            $key == $user->billing_information->country &&
                                                            old('country') == null)) selected @endif>{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="i-phone">{{ __('Phone') }}</label>
                                    <input type="text" name="phone" id="i-phone"
                                        class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                        value="{{ old('phone') ?? ($user->billing_information->phone ?? null) }}"
                                        placeholder="{{ __('Phone') }}">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 d-flex flex-column justify-content-start">
                        @if ($coupon && $coupon->type)
                            <div class="card border-0 mt-3 mt-lg-0 shadow-sm">
                                <div class="card-header">
                                    <div class="font-weight-medium py-1">{{ __('Order summary') }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">
                                                    <div>{{ __(':name plan', ['name' => $plan->name]) }}</div>

                                                    <div>
                                                        <div class="small text-muted">{{ __('Not billed') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                {{ __('Days') }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            {{ $coupon->days < 0 ? __('Unlimited') : $coupon->days }}
                                            <input type="hidden" name="coupon" value="{{ $coupon->code }}">
                                            <input type="hidden" name="coupon_set" value="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#" id="remove_coupon"
                                class="{{ !$coupon ? 'd-none' : '' }}">{{ __('Remove coupon?') }}</a>
                            <input type="hidden" name="interval" value="month">

                            <div class="mt-3">
                                <div class="small text-muted">{!! __('By continuing, you agree with the :terms.', [
                                    'terms' => mb_strtolower(
                                        '<a href="' . config('settings.legal_terms_url') . '" target="_blank">' . __('Terms and Condition') . '</a>',
                                    ),
                                ]) !!}</div>
                            </div>

                            <button type="submit" name="submit" class="btn btn-success btn-block mt-3">
                                {{ __('Start') }}
                            </button>
                        @else
                            <div class="card border-0 mt-3 mt-lg-0 shadow-sm">
                                <div class="card-header">
                                    <div class="font-weight-medium py-1">{{ __('Order summary') }}</div>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                        <label
                                            class="btn {{ $errors->has('interval') ? 'btn-outline-danger' : 'btn-outline-primary' }} w-100{{ request()->input('interval') == 'month' ? ' active' : '' }}"
                                            id="plan-month">
                                            <input type="radio" name="interval" value="month"
                                                @if (request()->input('interval') == 'month') checked="checked" @endif>{{ __('Monthly') }}
                                        </label>
                                        <label
                                            class="btn {{ $errors->has('interval') ? 'btn-outline-danger' : 'btn-outline-primary' }} w-100{{ request()->input('interval') == 'year' ? ' active' : '' }}"
                                            id="plan-year">
                                            <input type="radio" name="interval" value="year"
                                                @if (request()->input('interval') == 'year') checked="checked" @endif>{{ __('Yearly') }}

                                            @if ($plan->amount_month * 12 > $plan->amount_year)
                                                <span
                                                    class="badge bg-success text-white">-{{ number_format((($plan->amount_month * 12 - $plan->amount_year) / ($plan->amount_month * 12)) * 100, 0) }}%</span>
                                            @endif
                                        </label>
                                    </div>
                                    @if ($errors->has('interval'))
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $errors->first('interval') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item pt-0">
                                            <div class="row">
                                                <div class="col">
                                                    <div>{{ __(':name plan', ['name' => $plan->name]) }}</div>

                                                    <div class="d-none checkout-month">
                                                        <div class="small text-muted">
                                                            <span
                                                                class="d-none checkout-subscription">{!! __('Billed :interval.', ['interval' => mb_strtolower(__('Monthly'))]) !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-none checkout-year">
                                                        <div class="small text-muted">
                                                            <span
                                                                class="d-none checkout-subscription">{!! __('Billed :interval.', ['interval' => mb_strtolower(__('Yearly'))]) !!}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="d-none checkout-month">
                                                        {{ formatMoney($plan->amount_month, $plan->currency) }} <span
                                                            class="text-muted">{{ $plan->currency }}</span>
                                                    </div>
                                                    <div class="d-none checkout-year">
                                                        {{ formatMoney($plan->amount_year, $plan->currency) }} <span
                                                            class="text-muted">{{ $plan->currency }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        @if (!$coupon || $coupon->type == 0)
                                            @foreach ($taxRates as $taxRate)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div>{{ $taxRate->name }} ({{ $taxRate->percentage }}%
                                                                {{ $taxRate->type ? __('excl.') : __('incl.') }})</div>
                                                        </div>
                                                        <div class="col-auto">
                                                            @if ($taxRate->type)
                                                                <span class="d-none checkout-month">
                                                                    {{ formatMoney(checkoutExclusiveTax($plan->amount_month, $coupon->percentage ?? null, $taxRate->percentage, $inclTaxRatesPercentage), $plan->currency) }}
                                                                </span>
                                                                <span class="d-none checkout-year">
                                                                    {{ formatMoney(checkoutExclusiveTax($plan->amount_year, $coupon->percentage ?? null, $taxRate->percentage, $inclTaxRatesPercentage), $plan->currency) }}
                                                                </span>
                                                            @else
                                                                <span class="d-none checkout-month">
                                                                    {{ formatMoney(calculateInclusiveTax($plan->amount_month, $coupon->percentage ?? null, $taxRate->percentage, $inclTaxRatesPercentage), $plan->currency) }}
                                                                </span>
                                                                <span class="d-none checkout-year">
                                                                    {{ formatMoney(calculateInclusiveTax($plan->amount_year, $coupon->percentage ?? null, $taxRate->percentage, $inclTaxRatesPercentage), $plan->currency) }}
                                                                </span>
                                                            @endif

                                                            <span class="text-muted">{{ $plan->currency }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif

                                        @if ($coupon)
                                            <li class="list-group-item text-success">
                                                <div class="row">
                                                    <div class="col">
                                                        <div>{{ __('Discount') }} ({{ $coupon->percentage }}%)</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="d-none checkout-month">
                                                            -{{ formatMoney(calculateDiscount($plan->amount_month, $coupon->percentage), $plan->currency) }}
                                                        </span>
                                                        <span class="d-none checkout-year">
                                                            -{{ formatMoney(calculateDiscount($plan->amount_year, $coupon->percentage), $plan->currency) }}
                                                        </span>
                                                        <span class="text-muted">{{ $plan->currency }}</span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="coupon" value="{{ $coupon->code }}">
                                                <input type="hidden" name="coupon_set" value="true">
                                                <a href="#" id="remove_coupon"
                                                    class="{{ !$coupon ? 'd-none' : '' }}">{{ __('Remove coupon?') }}</a>
                                            </li>
                                        @endif

                                        @if ($plan->coupons && !$coupon)
                                            <li class="list-group-item">
                                                <a href="#" id="coupon"
                                                    class="{{ $errors->has('coupon') || old('coupon') ? 'd-none' : '' }}">{{ __('Have a coupon code?') }}</a>

                                                <div class="form-row {{ $errors->has('coupon') || old('coupon') ? '' : 'd-none' }}"
                                                    id="coupon-input">
                                                    <div class="col">
                                                        <div class="form-group mb-0">
                                                            <input type="text" required="required" name="coupon"
                                                                id="i-coupon"
                                                                class="form-control form-control-sm{{ $errors->has('coupon') || old('coupon') ? ' is-invalid' : '' }}"
                                                                value="{{ old('coupon') }}"
                                                                placeholder="{{ __('Coupon code') }}"
                                                                {{ $errors->has('coupon') || old('coupon') ? '' : ' disabled' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-auto">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm">{{ __('Apply') }}</button>
                                                    </div>

                                                    <div class="col-auto">
                                                        <a href="#" id="coupon-cancel"
                                                            class="btn btn-sm btn-light">{{ __('Cancel') }}</a>
                                                    </div>

                                                    @if ($errors->has('coupon'))
                                                        <div class="col-12">
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $errors->first('coupon') }}</strong>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>


                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="card-footer font-weight-bold">
                                    <div class="row">
                                        <div class="col">
                                            <span>{{ __('Total') }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="d-none checkout-month">
                                                {{ formatMoney(checkoutTotal($plan->amount_month, $coupon->percentage ?? null, $exclTaxRatesPercentage, $inclTaxRatesPercentage), $plan->currency) }}
                                            </span>
                                            <span class="d-none checkout-year">
                                                {{ formatMoney(checkoutTotal($plan->amount_year, $coupon->percentage ?? null, $exclTaxRatesPercentage, $inclTaxRatesPercentage), $plan->currency) }}
                                            </span>
                                            <span>{{ $plan->currency }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (getSetting('GOOGLE_RECAPTCHA') == 'enabled')
                                <div class="mt-3">
                                    <div class="g-recaptcha" data-sitekey="{{ getSetting('GOOGLE_RECAPTCHA_KEY') }}">
                                    </div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="invalid-feedback" role="alert" style="display: block">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-3">
                                <span class="small text-muted">
                                    <span class="checkout-subscription">
                                        {!! __(
                                            'By continuing, you agree with the :terms and authorize :title to charge your payment method on a recurring basis.',
                                            [
                                                'terms' => mb_strtolower(
                                                    '<a href="/pages/terms-and-conditions" target="_blank">' . __('Terms and Condition') . '</a>',
                                                ),
                                                'title' => '<strong>' . e(config('settings.title')) . '</strong>',
                                            ],
                                        ) !!} {{ __('You can cancel your subscription at any time.') }}
                                    </span>
                                </span>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary btn-block my-3">
                                <span class="d-none checkout-month">
                                    {{ __('Pay :amount :currency', ['amount' => formatMoney(checkoutTotal($plan->amount_month, $coupon->percentage ?? null, $exclTaxRatesPercentage, $inclTaxRatesPercentage), $plan->currency), 'currency' => e($plan->currency)]) }}
                                </span>
                                <span class="d-none checkout-year">
                                    {{ __('Pay :amount :currency', ['amount' => formatMoney(checkoutTotal($plan->amount_year, $coupon->percentage ?? null, $exclTaxRatesPercentage, $inclTaxRatesPercentage), $plan->currency), 'currency' => e($plan->currency)]) }}
                                </span>
                            </button>
                        @endif
                        @if (isDemoMode())
                            <div class="card border-0 shadow-sm mb-3 overflow-hidden">
                                <div class="card-header">
                                    <div class="font-weight-medium py-1">{{ __('Test cards (demo only)') }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <label class="d-block">
                                                <div class="row">
                                                    <div class="col">Stripe</div>
                                                    <div class="col-12 col-lg-auto mt-1 mt-lg-0">
                                                        <div class="text-muted">4242424242424242</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                        <li class="list-group-item">
                                            <label class="d-block">
                                                <div class="row">
                                                    <div class="col">PayPal</div>
                                                    <div class="col-12 col-lg-auto mt-1 mt-lg-0">
                                                        <div class="text-muted">4999991111111113</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection
