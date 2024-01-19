@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('smtp.update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail Mailer') }}
                        <i class="fa fa-info-circle info" title="{{ __('Mail Mailer. ex: smtp') }}"></i>
                    </label>
                    <input type="text" name="MAIL_MAILER"
                        class="form-control{{ $errors->has('MAIL_MAILER') ? ' is-invalid' : '' }}"
                        value="{{ old('MAIL_MAILER') ?? getSetting('MAIL_MAILER') }}" placeholder="{{ __('Mail Mailer') }}">
                    @if ($errors->has('MAIL_MAILER'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_MAILER') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail Host') }}
                        <i class="fa fa-info-circle info" title="{{ __('Mail Host. ex: smtp.gmail.com') }}"></i>
                    </label>
                    <input type="text" name="MAIL_HOST"
                        class="form-control{{ $errors->has('MAIL_HOST') ? ' is-invalid' : '' }}"
                        value="{{ old('MAIL_HOST') ?? getSetting('MAIL_HOST') }}" placeholder="{{ __('Mail Host') }}">
                    @if ($errors->has('MAIL_HOST'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_HOST') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail Port') }}
                        <i class="fa fa-info-circle info" title="{{ __('Mail Port. ex: 465') }}"></i>
                    </label>
                    <input type="number" name="MAIL_PORT"
                        class="form-control{{ $errors->has('MAIL_PORT') ? ' is-invalid' : '' }}"
                        value="{{ old('MAIL_PORT') ?? getSetting('MAIL_PORT') }}" placeholder="{{ __('Mail Port') }}">
                    @if ($errors->has('MAIL_PORT'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_PORT') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail Username') }}
                        <i class="fa fa-info-circle info" title="{{ __('Mail Username. ex. admin@yourdomain.in') }}"></i>
                    </label>
                    <input type="text" name="MAIL_USERNAME"
                        class="form-control{{ $errors->has('MAIL_USERNAME') ? ' is-invalid' : '' }}"
                        value="{{ old('MAIL_USERNAME') ?? getSetting('MAIL_USERNAME') }}"
                        placeholder="{{ __('Mail Username') }}">
                    @if ($errors->has('MAIL_USERNAME'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_USERNAME') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail Password') }}
                        <i class="fa fa-info-circle info" title="{{ __('Mail Password') }}"></i>
                    </label>
                    <input type="text" name="MAIL_PASSWORD"
                        class="form-control{{ $errors->has('MAIL_PASSWORD') ? ' is-invalid' : '' }}"
                        value="{{ isDemoMode() ? 'This field is hidden in the demo mode' : old('MAIL_PASSWORD') ?? getSetting('MAIL_PASSWORD') }}"
                        placeholder="{{ __('Mail Password') }}">
                    @if ($errors->has('MAIL_PASSWORD'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_PASSWORD') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail Encryption') }}
                        <i class="fa fa-info-circle info" title="{{ __('Mail Encryption. ex: ssl') }}"></i>
                    </label>
                    <input type="text" name="MAIL_ENCRYPTION"
                        class="form-control{{ $errors->has('MAIL_ENCRYPTION') ? ' is-invalid' : '' }}"
                        value="{{ old('MAIL_ENCRYPTION') ?? getSetting('MAIL_ENCRYPTION') }}"
                        placeholder="{{ __('Mail Encryption') }}">
                    @if ($errors->has('MAIL_ENCRYPTION'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_ENCRYPTION') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Mail From Address') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('Mail From Address. ex: admin@yourdomain.in') }}"></i>
                    </label>
                    <input type="text" name="MAIL_FROM_ADDRESS"
                        class="form-control{{ $errors->has('MAIL_FROM_ADDRESS') ? ' is-invalid' : '' }}"
                        value="{{ old('MAIL_FROM_ADDRESS') ?? getSetting('MAIL_FROM_ADDRESS') }}"
                        placeholder="{{ __('Mail From Address') }}">
                    @if ($errors->has('MAIL_FROM_ADDRESS'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MAIL_FROM_ADDRESS') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>

    <hr>

    <div class="test-smtp">
        <h4>{{ __('Test SMTP') }}</h4>
        <p>{{ __('Save the above form and test the SMTP details') }}</p>

        <div id="success" class="alert alert-success d-flex align-items-center" role="alert" hidden>
            <i class="fa fa-check-circle mr-3" style="font-size: 24px;"></i>
            <div>
                {{ __('An email has been sent successfully') }}
            </div>
        </div>

        <div id="error" class="alert alert-danger d-flex align-items-center" role="alert" hidden>
            <i class="fa fa-exclamation-triangle mr-3" style="font-size: 24px;"></i>
            <div class="log"></div>
        </div>
        
        <form id="testSmtp">
            <div class="row col-6">
                <input id="smtpEmail" type="text" class="form-control col-7"
                    placeholder="{{ __('Enter an email address') }}" maxlength="100" required />
                <button id="testSmtpButton" type="submit"
                    class="btn btn-warning col-4 ml-1">{{ __('Send Test Email') }}</button>
            </div>
        </form>
    </div>
@endsection
