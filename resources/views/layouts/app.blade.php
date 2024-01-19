<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getSelectedLanguage()->direction }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <style type="text/css">
        :root {
            --secondary-color: #536d79;
            --primary-color: {{ getSetting('PRIMARY_COLOR') }};
        }

    </style>
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/FAVICON.png') }}">
    @yield('style')

    <style>
        {!! getSetting('CUSTOM_CSS') !!}
    </style>
    {!! getSetting('CUSTOM_JS') !!}
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('storage/images/PRIMARY_LOGO.png') }}"
                    alt="{{ getSetting('APPLICATION_NAME') }}" class="logo-inner">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    @if (isDemoMode())
                        <button class="btn btn-success btn-sm">Demo Mode</button>
                        <button class="btn btn-warning ml-1 btn-sm" target="_blank"
                            onclick="location.href='https://codecanyon.net/item/jupitermeet-pro-video-conference/37367339'">Buy Now!</button>
                    @endif

                    @if (getLanguages()->count() > 1)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-globe"></i> {{ getSelectedLanguage()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach (getLanguages() as $language)
                                    <a class="dropdown-item @if (getSelectedLanguage()->name == $language->name) active @endif"
                                        href="{{ route('language', ['locale' => $language->code]) }}">{{ $language->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif

                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('pricing') && count(paymentGateways()) != 0 && getSetting('PAYMENT_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pricing') }}">{{ __('Pricing') }}</a>
                            </li>
                        @endif
                        @if (Route::has('login') && getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                        @if (Route::has('register') && getSetting('AUTH_MODE') == 'enabled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @if (Auth::user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin') }}">{{ __('Admin') }}</a>
                            </li>
                        @endif

                        @if (getSetting('AUTH_MODE') == 'enabled')
                            @if (Route::has('pricing') && count(paymentGateways()) != 0 && getSetting('PAYMENT_MODE') == 'enabled')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pricing') }}">{{ __('Pricing') }}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->username }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.profile') }}">
                                    {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                                             document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="pt-4 mb-5 mb-md-0">
            @yield('content')
        </main>

        <footer class="app-footer">
            <div class="container-fluid">
                <div class="row d-flex align-items-top">
                    <div class="col-12 col-md-9 text-md-left text-center pad-res">
                        <ul class="footer-links">
                            @foreach (getPages() as $page)
                                <li>
                                    <a href="{{ '/pages/' . $page->slug }}">{{ __($page->title) }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <p>{{ __('Copyright') }} &copy; {{ date('Y') }}
                            {{ getSetting('APPLICATION_NAME') }}. {{ __('All rights reserved') }}</p>
                    </div>
                    <div class="col-12 col-md-3 text-md-right text-center pad-res">
                        <div class="social-data">
                            <p><strong>{{ __('Share with your friends') }}</strong></p>
                            <ul class="social-links">
                                <li>
                                    <a href="" target="_blank" id="fbShare" rel="noreferrer">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_blank" id="twitterShare" rel="noreferrer">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_blank" id="waShare" rel="noreferrer">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="cookie">
            <p><i class="fa fa-cookie-bite"></i>
                {{ __('This website uses cookies to ensure you get the best experience on our website') }}
                <a href="/pages/privacy-policy"> {{ __('Learn more') }}</a>
            </p>
            <button class="btn btn-theme confirm-cookie">{{ __('Got it') }}</button>
        </div>
    </div>

    <script>
        const cookieConsent = "{{ getSetting('COOKIE_CONSENT') }}";
        const googleAnalyticsTrackingId = "{{ getSetting('GOOGLE_ANALYTICS_ID') }}";
        const socialInvitation = "{{ getSetting('SOCIAL_INVITATION') }}";

        const languages = {
            error_occurred: "{{ __('An error occurred, please try again') }}",
            data_updated: "{{ __('Data updated successfully') }}",
            no_meeting: "{{ __('The meeting does not exist') }}",
            meeting_created: "{{ __('The meeting has been created') }}",
            confirmation: "{{ __('Are you sure') }}",
            meeting_deleted: "{{ __('The meeting has been deleted') }}",
            link_copied: "{{ __('Meeting link has been copied to the clipboard') }}",
            meeting_updated: "{{ __('The meeting has been updated') }}",
            sending_invite: "{{ __('Sending the invitation') }}",
            invite_sent: "{{ __('Invitation has been sent') }}",
            inviteMessage: "{{ __('Hey there! Join me for a meeting at this link') }}",
            no_session: "{{ __('Could not get the session details') }}",
            kicked: "{{ __('You have been kicked out of the meeting') }}",
            uploading: "{{ __('Uploading the file') }}",
            meeting_ended: "{{ __('Meeting ended') }}",
            cant_connect: "{{ __('Could not connect to the server, please try again later') }}",
            invalid_password: "{{ __('The password is invalid') }}",
            no_device: "{{ __('Could not get the devices, please check the permissions and try again. Error') }}",
            approve: "{{ __('Approve') }}",
            decline: "{{ __('Decline') }}",
            request_join_meeting: "{{ __('Request to join the meeting') }}",
            request_declined: "{{ __('Your request has been declined by the moderator') }}",
            double_click: "{{ __('Double click on the video to make it fullscreen') }}",
            single_click: "{{ __('Single click on the video to turn picture-in-picture mode on') }}",
            error_message: "{{ __('An error occurred') }}",
            kick_user: "{{ __('Kick this user') }}",
            participant_joined: "{{ __('A participant has joined the meeting') }}",
            confirmation_kick: "{{ __('Are you sure you want to kick this user') }}",
            participant_left: "{{ __('A participant has left the meeting') }}",
            camera_on: "{{ __('Camera has been turned on') }}",
            camera_off: "{{ __('Camera has been turned off') }}",
            mic_unmute: "{{ __('Mic has been unmute') }}",
            mic_mute: "{{ __('Mic has been muted') }}",
            no_video: "{{ __('The video is not playing or has no video track') }}",
            no_pip: "{{ __('Picture-in-picture mode is not supported in this browser') }}",
            link_copied: "{{ __('The meeting invitation link has been copied to the clipboard') }}",
            cant_share_screen: "{{ __('Could not share the screen, please check the permissions and try again') }}",
            max_file_size: "{{ __('Maximum file size allowed (MB)') }}",
            view_file: "{{ __('View File') }}",
            hand_raised: "{{ __('Hand raised') }}",
            hand_raised_self: "{{ __('You raised hand') }}",
            your_screen: "{{ __('Your screen') }}",
            not_started: "{{ __('The meeting has not been started yet') }}",
            meeting_full: "{{ __('The meeting is full') }}",
            please_wait: "{{ __('Please wait while the moderator check your request') }}",
            request_record_meeting: "{{ __('Request to record the meeting') }}",
            record_request_declined: "{{ __('You recording request was not approved') }}",
            feature_not_supported: "{{ __('This feature is not yet supported in your browser') }}",
            feature_not_available: "{{ __('This feature is not available in the current meeting plan') }}",
            password: "{{ __('Password: ') }}",
            calendar_check: "{{ __('Please set a date and time') }}",
            recording_started: "{{ __('The recording has been started') }}",
            token_copied: "{{ __('API Token has been copied to the clipboard') }}",
            screen: "{{ __('Screen') }}"
        }
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('script')
</body>

</html>
