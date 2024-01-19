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
                                <h3>{{ __('You are just a click away') }}</h3>
                            </div>
                            <div class="bg-set">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 auth-enterdata h-100">
                            <div class="card auth-info">
                                <div class="card-header text-center">
                                    {{ __('Verify Your Email Address') }}
                                </div>
                                <div class="card-body">
                                    @if (session('resent'))
                                        <div class="alert alert-success" role="alert">
                                            {{ __('A fresh verification link has been sent to your email address') }}
                                        </div>
                                    @endif

                                    {{ __('Before proceeding, please check your email for a verification link') }}
                                    {{ __('If you did not receive the email') }},
                                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
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
