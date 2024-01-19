@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Profile') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link @if ($route == 'basic') active @endif"
                                href="{{ route('profile.profile') }}" role="tab" aria-controls="vert-tabs-home"
                                aria-selected="true">{{ __('Basic Information') }}</a>
                            <a class="nav-link @if ($route == 'security') active @endif"
                                href="{{ route('profile.security') }}" role="tab" aria-controls="vert-tabs-profile"
                                aria-selected="false">{{ __('Security') }}</a>
                            <a class="nav-link @if ($route == 'plan') active @endif"
                                href="{{ route('profile.plan') }}" role="tab" aria-controls="vert-tabs-messages"
                                aria-selected="false">{{ __('My Plan') }}</a>
                            <a class="nav-link @if ($route == 'payments') active @endif"
                                href="{{ route('profile.payments') }}" role="tab" aria-controls="vert-tabs-settings"
                                aria-selected="false">{{ __('Payments') }}</a>
                            <a class="nav-link @if ($route == 'api') active @endif"
                                href="{{ route('profile.api') }}" role="tab" aria-controls="vert-tabs-settings"
                                aria-selected="false">{{ __('API Token') }}</a>
                            <a class="nav-link @if ($route == 'contacts' || $route == 'contactCreate' || $route == 'contactEdit' || $route == 'contactImport') active @endif"
                                href="{{ route('profile.contacts') }}" role="tab" aria-controls="vert-tabs-settings"
                                aria-selected="false">{{ __('Contacts') }}</a>
                            <a class="nav-link @if ($route == 'tfa') active @endif"
                                href="{{ route('profile.tfa') }}" role="tab" aria-controls="vert-tabs-settings"
                                aria-selected="false">{{ __('Two Factor Authentication') }}</a>
                        </div>
                    </div>
                    <div class="col-7 col-sm-9">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            <div class="tab-pane text-left fade active show" role="tabpanel"
                                aria-labelledby="vert-tabs-home-tab">
                                @yield('profile-content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
