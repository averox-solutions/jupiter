@extends('admin.global-config.index')

@section('global-config-content')
    <div class="form-group">
        <label for="api_token">{{ __('API Token') }}
            <i class="fa fa-info-circle info" title="{{ __('Use this API Token to create users with webhook') }}"></i>
        </label>
        <input type="text" id="api_token" class="form-control" value="{{ isDemoMode() ? 'This field is hidden in the demo mode' : getSetting('API_TOKEN') }}" disabled>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button type="button" id="copyApiToken" class="btn btn-primary">{{ __('Copy') }}</button>
        </div>
    </div>
@endsection
