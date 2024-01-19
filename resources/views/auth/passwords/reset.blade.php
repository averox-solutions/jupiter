@extends('layouts.app')

@section('page', __('Reset Password'))
@section('title', getSetting('APPLICATION_NAME') . ' | ' . __('Reset Password'))

@section('content')
    <section class="auth-section">
        <div class="container auth-container">
            <div class="row align-items-center h-100">
                <div class="col-md-1"></div>
                <div class="col-12 col-md-10 main-authsection h-100 bg-primary">
                    <div class="row h-100">
                        <div class="col-12 col-md-6 h-100 auth-text-section d-none d-md-block">
                            <div class="text-center auth-info">
                                <h3>{{ __('Create a new password') }}</h3>
                                {{ __('Already have the password?') }}<br /><a href="{{ route('login') }}"
                                    class="white-text"><u>{{ __('Login') }}</u></a>
                            </div>
                            <div class="bg-set"></div>
                        </div>
                        <div class="col-12 col-md-6 auth-enterdata h-100">
                            <div class="card auth-info">
                                <div class="card-header text-center">
                                    {{ __('Reset Password') }}
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input placeholder="{{ __('E-Mail Address') }}" id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ $email ?? old('email') }}" maxlength="50" required
                                                    autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input placeholder="{{ __('Password') }}" id="password" type="password"
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

                                        <div class="form-group row mb-0">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Reset Password') }}
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
