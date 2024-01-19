@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('style')
    <style>
        h6 {
            width: 100%;
            text-align: center;
            border-bottom: 1px solid #000;
            line-height: 0.1em;
            margin: 10px 0 20px;
        }

        h6 span {
            background: #fff;
            padding: 0 10px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <form action="{{ route('admin.plans.new') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="i-name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                value="{{ old('name') }}" placeholder="{{ __('Name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-currency">{{ __('Currency') }}</label>
                            <select name="currency" id="i-currency"
                                class="custom-select{{ $errors->has('currency') ? ' is-invalid' : '' }}">
                                @foreach ($currencies as $key => $value)
                                    <option value="{{ $key }}" @if (old('currency') == $key) selected @endif>
                                        {{ $key }} - {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('currency'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('currency') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-amount-month">{{ __('Monthly amount') }}</label>
                            <input type="text" name="amount_month" id="i-amount-month"
                                class="form-control{{ $errors->has('amount_month') ? ' is-invalid' : '' }}"
                                value="{{ old('amount_month') }}" placeholder="{{ __('Monthly amount') }}">
                            @if ($errors->has('amount_month'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount_month') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-amount-year">{{ __('Yearly amount') }}</label>
                            <input type="text" name="amount_year" id="i-amount-year"
                                class="form-control{{ $errors->has('amount_year') ? ' is-invalid' : '' }}"
                                value="{{ old('amount_year') }}" placeholder="{{ __('Yearly amount') }}">
                            @if ($errors->has('amount_year'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount_year') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-tax-rates">{{ __('Tax Rates') }}</label>
                            <select name="tax_rates[]" id="i-tax-rates"
                                class="custom-select{{ $errors->has('tax_rates') ? ' is-invalid' : '' }}" size="3"
                                multiple>
                                <option value="">None</option>
                                @foreach ($taxRates as $taxRate)
                                    <option value="{{ $taxRate->id }}" @if (old('tax_rates') !== null && in_array($taxRate->id, old('tax_rates'))) selected @endif>
                                        {{ $taxRate->name }}
                                        ({{ number_format($taxRate->percentage, 2, __('.'), __(',')) }}%
                                        {{ $taxRate->type ? __('Exclusive') : __('Inclusive') }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('tax_rates'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('tax_rates') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-coupons">{{ __('Coupons') }}</label>
                            <select name="coupons[]" id="i-coupons"
                                class="custom-select{{ $errors->has('coupons') ? ' is-invalid' : '' }}" size="3"
                                multiple>
                                <option value="">None</option>
                                @foreach ($coupons as $coupon)
                                    <option value="{{ $coupon->id }}" @if (old('coupons') !== null && in_array($coupon->id, old('coupons'))) selected @endif>
                                        {{ $coupon->name }}
                                        ({{ number_format($coupon->percentage, 2, __('.'), __(',')) }}%
                                        {{ $coupon->type ? __('Redeemable') : __('Discount') }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('coupons'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('coupons') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-description">{{ __('Description') }}</label>
                            <textarea id="i-description" name="description" rows="2" cols="50"
                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') }}"
                                placeholder="{{ __('Description') }}"></textarea>
                            @if ($errors->has('description'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <h6><span>{{ __('Features') }}</span></h6>

                <div class="row">
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-video-chat">{{ __('Text Chat') }}</label>
                            <select name="features[text_chat]" id="i-features-global-domains"
                                class="custom-select{{ $errors->has('features.text_chat') ? ' is-invalid' : '' }}">
                                @foreach ([1 => __('On'), 0 => __('Off')] as $key => $value)
                                    <option value="{{ $key }}" @if (old('features.text_chat') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('features.text_chat'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.text_chat') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-video-chat">{{ __('File Sharing') }}</label>
                            <select name="features[file_share]" id="i-features-global-domains"
                                class="custom-select{{ $errors->has('features.file_share') ? ' is-invalid' : '' }}">
                                @foreach ([1 => __('On'), 0 => __('Off')] as $key => $value)
                                    <option value="{{ $key }}"
                                        @if (old('features.file_share') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('features.file_share'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.file_share') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-video-chat">{{ __('Screen Sharing') }}</label>
                            <select name="features[screen_share]" id="i-features-global-domains"
                                class="custom-select{{ $errors->has('features.screen_share') ? ' is-invalid' : '' }}">
                                @foreach ([1 => __('On'), 0 => __('Off')] as $key => $value)
                                    <option value="{{ $key }}"
                                        @if (old('features.screen_share') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('features.screen_share'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.screen_share') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-video-chat">{{ __('Whiteboard') }}</label>
                            <select name="features[whiteboard]" id="i-features-global-domains"
                                class="custom-select{{ $errors->has('features.whiteboard') ? ' is-invalid' : '' }}">
                                @foreach ([1 => __('On'), 0 => __('Off')] as $key => $value)
                                    <option value="{{ $key }}"
                                        @if (old('features.whiteboard') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('features.whiteboard'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.whiteboard') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-video-chat">{{ __('Hand Raise') }}</label>
                            <select name="features[hand_raise]" id="i-features-global-domains"
                                class="custom-select{{ $errors->has('features.hand_raise') ? ' is-invalid' : '' }}">
                                @foreach ([1 => __('On'), 0 => __('Off')] as $key => $value)
                                    <option value="{{ $key }}"
                                        @if (old('features.hand_raise') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('features.hand_raise'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.hand_raise') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-video-chat">{{ __('Recording') }}</label>
                            <select name="features[recording]" id="i-features-global-domains"
                                class="custom-select{{ $errors->has('features.recording') ? ' is-invalid' : '' }}">
                                @foreach ([1 => __('On'), 0 => __('Off')] as $key => $value)
                                    <option value="{{ $key }}"
                                        @if (old('features.recording') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('features.recording'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.recording') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-links">{{ __('Number of Meetings') }}</label>
                            <input type="number" name="features[meeting_no]"
                                class="form-control{{ $errors->has('features.meeting_no') ? ' is-invalid' : '' }}"
                                value="{{ old('features.meeting_no') ?? 0 }}" placeholder="{{ __('-1 for Unlimited') }}">
                            @if ($errors->has('features.meeting_no'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.meeting_no') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-links">{{ __('Time limit') }}</label>
                            <input type="number" name="features[time_limit]"
                                class="form-control{{ $errors->has('features.time_limit') ? ' is-invalid' : '' }}"
                                value="{{ old('features.time_limit') ?? 0 }}" placeholder="{{ __('-1 for Unlimited') }}">
                            @if ($errors->has('features.time_limit'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.time_limit') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="form-group">
                            <label for="i-features-links">{{ __('User limit') }}</label>
                            <input type="number" name="features[user_limit]"
                                class="form-control{{ $errors->has('features.user_limit') ? ' is-invalid' : '' }}"
                                value="{{ old('features.user_limit') ?? 0 }}" placeholder="{{ __('-1 for Unlimited') }}">
                            @if ($errors->has('features.user_limit'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('features.user_limit') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" id="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('admin.plans') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
