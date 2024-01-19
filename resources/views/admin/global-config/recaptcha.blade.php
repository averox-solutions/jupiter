@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('recaptcha.update') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Google reCAPTCHA') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This will add a Google reCAPTCHA validation on the checkout page.') }}"></i>
                    </label>
                    <select name="GOOGLE_RECAPTCHA"
                        class="form-control{{ $errors->has('GOOGLE_RECAPTCHA') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('GOOGLE_RECAPTCHA') ? old('GOOGLE_RECAPTCHA') == 'enabled' : getSetting('GOOGLE_RECAPTCHA') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('GOOGLE_RECAPTCHA') ? old('GOOGLE_RECAPTCHA') == 'disabled' : getSetting('GOOGLE_RECAPTCHA') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('GOOGLE_RECAPTCHA'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('GOOGLE_RECAPTCHA') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Google reCAPTCHA Key') }}
                        <i class="fa fa-info-circle info" title="{{ __('Google reCAPTCHA Key') }}"></i>
                    </label>
                    <input type="text" name="GOOGLE_RECAPTCHA_KEY"
                        class="form-control{{ $errors->has('GOOGLE_RECAPTCHA_KEY') ? ' is-invalid' : '' }}"
                        value="{{ old('GOOGLE_RECAPTCHA_KEY') ?? getSetting('GOOGLE_RECAPTCHA_KEY') }}"
                        placeholder="{{ __('Google reCAPTCHA Key') }}">
                    @if ($errors->has('GOOGLE_RECAPTCHA_KEY'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('GOOGLE_RECAPTCHA_KEY') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Google reCAPTCHA Secret') }}
                        <i class="fa fa-info-circle info" title="{{ __('Google reCAPTCHA Secret') }}"></i>
                    </label>
                    <input type="text" name="GOOGLE_RECAPTCHA_SECRET"
                        class="form-control{{ $errors->has('GOOGLE_RECAPTCHA_SECRET') ? ' is-invalid' : '' }}"
                        value="{{ old('GOOGLE_RECAPTCHA_SECRET') ?? getSetting('GOOGLE_RECAPTCHA_SECRET') }}"
                        placeholder="{{ __('Google reCAPTCHA Secret') }}">
                    @if ($errors->has('GOOGLE_RECAPTCHA_SECRET'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('GOOGLE_RECAPTCHA_SECRET') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
