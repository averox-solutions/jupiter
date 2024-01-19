@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <section class="auth-section">
        <div class="container auth-container">
            <div class="row align-items-center h-100">
                <div class="col-md-1"></div>
                <div class="col-12 col-md-10 main-authsection h-100 bg-primary">
                    <div class="row h-100">
                        <div class="col-12 col-md-6 h-100 d-none d-md-block auth-text-section">
                            <div class="text-center auth-info">
                                <h3>{{ __('Welcome back!') }}</h3>
                                @if (Route::has('register'))
                                    {{ __('Don\'t have an account yet?') }}<br /><a href="{{ route('register') }}"
                                        class="white-text"><u>{{ __('Register') }}</u></a>
                                @endif
                            </div>
                            <div class="bg-set"></div>
                        </div>
                        <div class="col-12 col-md-6 auth-enterdata h-100">
                            <div class="card auth-info mb-0">
                                <div class="card-header text-center">
                                    {{ __('Login') }}
                                </div>
                                <div class="card-body">
                                    @if (session('verify'))
                                        <div class="alert alert-warning" role="alert">
                                            {{ session('verify') }}
                                        </div>
                                    @endif

                                    <form id="login" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input id="email" type="text"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" placeholder="{{ __('E-Mail Address or Username') }}"
                                                    maxlength="50" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="{{ __('Password') }}" name="password" maxlength="50"
                                                    required autocomplete="current-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-12 text-center">
                                                <button id="loginButton" type="submit" class="btn btn-primary mb-2">
                                                    {{ __('Login') }}
                                                </button>

                                                @if (Route::has('password.request'))
                                                    <p class="mb-0">
                                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password') }}
                                                        </a>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @if (isDemoMode())
                                    <div>
                                        <select id="autoLogin" class="form-control">
                                            <option value="">Auto login as (demo only)</option>
                                            <option value="admin">Admin</option>
                                            <option value="user_1">User 1</option>
                                            <option value="user_2">User 2</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
