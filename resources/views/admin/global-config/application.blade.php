@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('application.update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Auth Mode') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This mode will turn on the register, dashboard, profile, etc modules. If this mode is off use the \'login\' URL manually to log in.') }}"></i>
                    </label>
                    <select name="AUTH_MODE"
                        class="form-control{{ $errors->has('AUTH_MODE') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('AUTH_MODE') ? old('AUTH_MODE') == 'enabled' : getSetting('AUTH_MODE') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('AUTH_MODE') ? old('AUTH_MODE') == 'disabled' : getSetting('AUTH_MODE') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('AUTH_MODE'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('AUTH_MODE') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Payment Mode') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This mode will turn on the payment module. An extended license is required.') }}"></i>
                    </label>
                    <select name="PAYMENT_MODE"
                        class="form-control{{ $errors->has('PAYMENT_MODE') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('PAYMENT_MODE') ? old('PAYMENT_MODE') == 'enabled' : getSetting('PAYMENT_MODE') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('PAYMENT_MODE') ? old('PAYMENT_MODE') == 'disabled' : getSetting('PAYMENT_MODE') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('PAYMENT_MODE'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('PAYMENT_MODE') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Landing page') }}
                        <i class="fa fa-info-circle info" title="{{ __('Set landing page on or off.') }}"></i>
                    </label>
                    <select name="LANDING_PAGE"
                        class="form-control{{ $errors->has('LANDING_PAGE') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('LANDING_PAGE') ? old('LANDING_PAGE') == 'enabled' : getSetting('LANDING_PAGE') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('LANDING_PAGE') ? old('LANDING_PAGE') == 'disabled' : getSetting('LANDING_PAGE') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('LANDING_PAGE'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('LANDING_PAGE') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Cookie Consent') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('If on, the system will display a cookie consent popup to the visitors.') }}"></i>
                    </label>
                    <select name="COOKIE_CONSENT"
                        class="form-control{{ $errors->has('COOKIE_CONSENT') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('COOKIE_CONSENT') ? old('COOKIE_CONSENT') == 'enabled' : getSetting('COOKIE_CONSENT') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('COOKIE_CONSENT') ? old('COOKIE_CONSENT') == 'disabled' : getSetting('COOKIE_CONSENT') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('COOKIE_CONSENT'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('COOKIE_CONSENT') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Registration') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('If off, the system will disable the registration option for guests') }}"></i>
                    </label>
                    <select name="REGISTRATION"
                        class="form-control{{ $errors->has('REGISTRATION') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('REGISTRATION') ? old('REGISTRATION') == 'enabled' : getSetting('REGISTRATION') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('REGISTRATION') ? old('REGISTRATION') == 'disabled' : getSetting('REGISTRATION') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('REGISTRATION'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('REGISTRATION') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Verify Users') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('If on, the system will send an email to the newly registered user. He must verify it before logging in') }}"></i>
                    </label>
                    <select name="VERIFY_USERS"
                        class="form-control{{ $errors->has('VERIFY_USERS') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('VERIFY_USERS') ? old('VERIFY_USERS') == 'enabled' : getSetting('VERIFY_USERS') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('VERIFY_USERS') ? old('VERIFY_USERS') == 'disabled' : getSetting('VERIFY_USERS') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('VERIFY_USERS'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('VERIFY_USERS') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Google analytics ID') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('Google Analytics tracking ID. Set null to turn it off. It uses the format G-XXXXXXX.') }}"></i>
                    </label>
                    <input type="text" name="GOOGLE_ANALYTICS_ID"
                        class="form-control{{ $errors->has('GOOGLE_ANALYTICS_ID') ? ' is-invalid' : '' }}"
                        value="{{ old('GOOGLE_ANALYTICS_ID') ?? getSetting('GOOGLE_ANALYTICS_ID') }}"
                        placeholder="{{ __('Google analytics ID') }}">
                    @if ($errors->has('GOOGLE_ANALYTICS_ID'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('GOOGLE_ANALYTICS_ID') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Social Invitation') }}
                        <i class="fa fa-info-circle info" title="{{ __('Social invitation link message.') }}"></i>
                    </label>
                    <textarea class="form-control{{ $errors->has('SOCIAL_INVITATION') ? ' is-invalid' : '' }}" name="SOCIAL_INVITATION"
                        placeholder="{{ __('Social Invitation') }}"
                        rows="3">{{ old('SOCIAL_INVITATION') ?? getSetting('SOCIAL_INVITATION') }}</textarea>
                    @if ($errors->has('SOCIAL_INVITATION'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('SOCIAL_INVITATION') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
