@extends('layouts.app')

@section('content')
    <section class="auth-section">
        <div class="container auth-container">
            <div class="row align-items-center h-100">
                <div class="col-md-1"></div>
                <div class="col-12 col-md-10 main-authsection h-100 bg-primary">
                    <div class="row h-100">
                        <div class="col-12 col-md-6 h-100 auth-text-section d-none d-md-block">
                            <div class="text-center auth-info">
                                <h3>Welcome Back!</h3>
                                @if (Route::has('register'))
                                    {{ __('Don\'t have an account yet?') }}<br /><a href="{{ route('register') }}"
                                        class="white-text"><u>{{ __('Register') }}</u></a>
                                @endif
                            </div>
                            <div class="bg-set">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 auth-enterdata h-100">
                            <div class="card auth-info">
                                <div class="card-header text-center">
                                    {{ __('Confirm Password') }}
                                </div>
                                <div class="card-body">
                                    {{ __('Please confirm your password before continuing') }}

                                    <form method="POST" action="{{ route('password.confirm') }}">
                                        @csrf

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input id="password" placeholder="{{ __('Password') }}" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Confirm Password') }}
                                                </button>

                                                @if (Route::has('password.request'))
                                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                                        {{ __('Forgot Your Password') }}
                                                    </a>
                                                @endif
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
        </div>
    </section>
@endsection
