@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            {{ __('URL') }}: <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            {{ __('Status') }}: <span id="status" class="badge p-1">-</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button id="checkSignaling" class="btn btn-info mt-2">{{ __('Refresh') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4>{{ __('Troubleshooting') }}</h4>
                    <div class="callout callout-info">
                        <ul>
                            <li>{{ __('Make sure, the URL is correct') }}</li>
                            <li>{{ __('Make sure, the /server/.env file has been updated as per the documentation') }}
                            </li>
                            <li>{{ __('Make sure, the NodeJS service is started as per the documentation') }}</li>
                            <li>{{ __('Make sure, the required ports are allowed in the Firewall as per the documentation') }}
                            </li>
                            <li>{{ __('Make sure, the SSL certificates are valid') }}</li>
                            <li>{{ __('If you are using Cloudflare, make sure you use 8443 port') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $("#checkSignaling").trigger('click');
    </script>
@endsection
