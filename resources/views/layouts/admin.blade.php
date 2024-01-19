@php
$path = request()
    ->route()
    ->getName();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getSelectedLanguage()->direction }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/FAVICON.png') }}">
    @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                @if (isDemoMode())
                    <button class="btn btn-primary btn-sm">Demo Mode</button>
                    <button class="btn btn-warning ml-1 btn-sm"
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
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin') }}">{{ __('Admin') }}</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>

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

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin') }}" class="brand-link">
                <img src="{{ asset('storage/images/SECONDARY_LOGO.png') }}"
                    alt="{{ getSetting('APPLICATION_NAME') }}" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">{{ getSetting('APPLICATION_NAME') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('admin') }}" class="nav-link" data-name="admin">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    {{ __('Dashboard') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('meetings') }}" class="nav-link" data-name="meetings">
                                <i class="nav-icon fa fa-video"></i>
                                <p>
                                    {{ __('Meetings') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users') }}" class="nav-link" data-name="users">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    {{ __('Users') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('global-config') }}" class="nav-link" data-name="global-config">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    {{ __('Global Configuration') }}
                                </p>
                            </a>
                        </li>

                        @if (in_array($path, ['admin.plans', 'admin.plans.new', 'admin.plans.edit', 'admin.coupons', 'admin.coupons.new', 'admin.coupons.edit', 'admin.tax_rates', 'admin.tax_rates.new', 'admin.tax_rates.edit', 'admin.transaction', 'admin.payment_gateways']))
                            <li class="nav-item has-treeview menu-open">
                            @else
                            <li class="nav-item has-treeview">
                        @endif
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-money-check-alt"></i>
                            <p>
                                {{ __('Manage Payment') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.payment_gateways') }}" class="nav-link"
                                    data-name="payment-gateways">
                                    <i class="nav-icon fa fa-coins"></i>
                                    <p>
                                        {{ __('Payment Gateways') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.plans') }}" class="nav-link" data-name="plans">
                                    <i class="nav-icon fa fa-list-alt"></i>
                                    <p>
                                        {{ __('Plans') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.coupons') }}" class="nav-link" data-name="coupons">
                                    <i class="nav-icon fa fa-tags"></i>
                                    <p>
                                        {{ __('Coupons') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.tax_rates') }}" class="nav-link"
                                    data-name="tax-rates">
                                    <i class="nav-icon fa fa-percentage"></i>
                                    <p>
                                        {{ __('Tax Rates') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.transaction') }}" class="nav-link"
                                    data-name="transaction">
                                    <i class="nav-icon fa fa-file-invoice-dollar"></i>
                                    <p>
                                        {{ __('Transaction') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('languages') }}" class="nav-link" data-name="languages">
                                <i class="nav-icon fa fa-language"></i>
                                <p>
                                    {{ __('Languages') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('signaling') }}" class="nav-link" data-name="signaling">
                                <i class="nav-icon fa fa-signal"></i>
                                <p>
                                    {{ __('Signaling Server') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pages') }}" class="nav-link" data-name="pages">
                                <i class="nav-icon fa fa-file-alt"></i>
                                <p>
                                    {{ __('Pages') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('update') }}" class="nav-link" data-name="update">
                                <i class="nav-icon fa fa-download"></i>
                                <p>
                                    {{ __('Manage Update') }}
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('license') }}" class="nav-link" data-name="license">
                                <i class="nav-icon fa fa-id-badge"></i>
                                <p>
                                    {{ __('Manage License') }}
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">@yield('page')</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>{{ __('Copyright') }} &copy; {{ date('Y') }}
                {{ getSetting('APPLICATION_NAME') }}.</strong>
            {{ __('All rights reserved') }}
            <div class="float-right d-none d-sm-inline-block">
                <b>{{ __('Version') }}</b> {{ getSetting('VERSION') }}
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        const languages = {
            user_registration: "{{ __('User Registration') }}",
            meetings: "{{ __('Meetings') }}",
            error_occurred: "{{ __('An error occurred, please try again') }}",
            data_updated: "{{ __('Data updated successfully') }}",
            valid_license: "{{ __('Your license is valid. Type: ') }}",
            invalid_license: "{{ __('Your license is invalid. Error: ') }}",
            confirmation: "{{ __('Are you sure') }}",
            license_uninstalled: "{{ __('License uninstalled') }}",
            license_uninstalled_failed: "{{ __('License uninstallation failed. Error: ') }}",
            update_available: "{{ __('An update is available: Version: ') }}",
            already_latest_version: "{{ __('Application is already at latest version. Version: ') }}",
            application_updated: "{{ __('The application has been successfully updated to the latest version') }}",
            update_failed: "{{ __('Update failed. Error: ') }}",
            data_added: "{{ __('Data added successfully') }}",
            data_deleted: "{{ __('Data deleted successfully') }}",
            all: "{{ __('All') }}",
            info: "{{ __('Showing page _PAGE_ of _PAGES_') }}",
            lengthMenu: "{{ __('Display _MENU_ records per page') }}",
            zeroRecords: "{{ __('Nothing found - sorry') }}",
            infoEmpty: "{{ __('No records available') }}",
            infoFiltered: "{{ __('filtered from _MAX_ total records') }}",
            next: "{{ __('Next') }}",
            previous: "{{ __('Previous') }}",
            search: "{{ __('Search') }}",
            jan: "{{ __('Jan') }}",
            feb: "{{ __('Feb') }}",
            mar: "{{ __('Mar') }}",
            apr: "{{ __('Apr') }}",
            may: "{{ __('May') }}",
            june: "{{ __('June') }}",
            jul: "{{ __('Jul') }}",
            aug: "{{ __('Aug') }}",
            sep: "{{ __('Sep') }}",
            oct: "{{ __('Oct') }}",
            nov: "{{ __('Nov') }}",
            dec: "{{ __('Dec') }}",
            link_copied: "{{ __('The link has been copied to the clipboard') }}",
            code_copied: "{{ __('The code has been copied to the clipboard') }}",
            token_copied: "{{ __('API Token has been copied to the clipboard') }}",
            free: "{{ __('Free') }}",
            paid: "{{ __('Paid') }}",
            income: "{{ __('Income') }}",
        };
    </script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    @yield('script')
</body>

</html>
