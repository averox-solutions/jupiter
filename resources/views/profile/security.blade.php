@extends('profile.index')

@section('profile-content')
    @include('include.message')

    <form action="{{ route('profile.security') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="i-current-password">{{ __('Current password') }}</label>
            <input type="password" name="current_password" id="i-current-password"
                class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}"
                placeholder="{{ __('Current password') }}">
            @if ($errors->has('current_password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('current_password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="i-password">{{ __('New password') }}</label>
            <input type="password" name="password" id="i-password"
                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                placeholder="{{ __('New password') }}">
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="i-password-confirmation">{{ __('Confirm new password') }}</label>
            <input type="password" name="password_confirmation" id="i-password-confirmation"
                class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                placeholder="{{ __('Confirm new password') }}">
            @if ($errors->has('password_confirmation'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>

        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
