@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <div class="row">
                <div class="col-4 col-sm-2">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link @if ($route == 'basic') active @endif"
                            href="{{ route('global-config') }}" role="tab" aria-controls="vert-tabs-home"
                            aria-selected="true">{{ __('Basic') }}</a>
                        <a class="nav-link @if ($route == 'application') active @endif"
                            href="{{ route('global-config.application') }}" role="tab" aria-controls="vert-tabs-profile"
                            aria-selected="false">{{ __('Application') }}</a>
                        <a class="nav-link @if ($route == 'meeting') active @endif"
                            href="{{ route('global-config.meeting') }}" role="tab" aria-controls="vert-tabs-settings"
                            aria-selected="false">{{ __('Meeting') }}</a>
                        <a class="nav-link @if ($route == 'js') active @endif"
                            href="{{ route('global-config.js') }}" role="tab" aria-controls="vert-tabs-settings"
                            aria-selected="false">{{ __('Custom JS') }}</a>
                        <a class="nav-link @if ($route == 'css') active @endif"
                            href="{{ route('global-config.css') }}" role="tab" aria-controls="vert-tabs-settings"
                            aria-selected="false">{{ __('Custom CSS') }}</a>
                        <a class="nav-link @if ($route == 'smtp') active @endif"
                            href="{{ route('global-config.smtp') }}" role="tab" aria-controls="vert-tabs-settings"
                            aria-selected="false">{{ __('SMTP') }}</a>
                        <a class="nav-link @if ($route == 'api') active @endif"
                            href="{{ route('global-config.api') }}" role="tab" aria-controls="vert-tabs-settings"
                            aria-selected="false">{{ __('API Token') }}</a>
                        <a class="nav-link @if ($route == 'recaptcha') active @endif"
                            href="{{ route('global-config.recaptcha') }}" role="tab" aria-controls="vert-tabs-settings"
                            aria-selected="false">{{ __('Google reCAPTCHA') }}</a>
                    </div>
                </div>
                <div class="col-8 col-sm-10">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        <div class="tab-pane text-left fade active show" role="tabpanel"
                            aria-labelledby="vert-tabs-home-tab">
                            @yield('global-config-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
