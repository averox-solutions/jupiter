@extends('profile.index')

@section('profile-content')
    <div class="form-group">
        <label for="api_token">{{ __('API Token') }}</label>
        <input type="text" id="api_token" class="form-control" value="{{ isDemoMode() ? 'This field is hidden in the demo mode' : $api_token }}" disabled>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button type="button" id="copyApiToken" class="btn btn-primary">{{ __('Copy') }}</button>
        </div>
    </div>
@endsection
