@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . __('Register'))

@section('content')
    <section class="auth-section">
        <div class="container auth-container">
            <div class="row align-items-center h-100">
                <div class="col-md-1"></div>
                <div class="col-12 col-md-10 main-authsection h-100 bg-primary">
                    <div class="row h-100">
                        <div class="col-12 col-md-6 h-100 auth-text-section d-none d-md-block">
                            <div class="text-center auth-info">
                                <h3>{{ __('Create an account') }}</h3>
                                {{ __('Already have an account?') }}<br /><a href="{{ route('login') }}"
                                    class="white-text"><u>{{ __('Login') }}</u></a>
                            </div>
                            <div class="bg-set"></div>
                        </div>
                        <div class="col-12 col-md-6 auth-enterdata h-100">
                            <div class="card auth-info">
                                <div class="card-header text-center">
                                    {{ __('Register') }}
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input id="username" type="text" placeholder="{{ __('Username') }}"
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    name="username" value="{{ old('username') }}" required
                                                    autocomplete="username" maxlength="20" autofocus>

                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input id="email" placeholder="{{ __('E-Mail Address') }}" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" maxlength="50" required
                                                    autocomplete="email">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input id="password" placeholder="{{ __('Password') }}" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" maxlength="50" required autocomplete="new-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input placeholder="{{ __('Confirm Password') }}" id="password-confirm"
                                                    type="password" class="form-control" name="password_confirmation"
                                                    maxlength="50" required autocomplete="new-password">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input @error('terms') is-invalid @enderror"
                                                        type="checkbox" name="terms" id="terms">

                                                    <label class="form-check-label" for="terms">
                                                        {{ __('I agree to the') }} <a
                                                            href="/pages/terms-and-conditions"
                                                            target="_blank">{{ __('Terms & Conditions') }}</a>
                                                    </label>

                                                    @error('terms')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Register') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
