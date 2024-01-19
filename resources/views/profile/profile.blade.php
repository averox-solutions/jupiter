@extends('profile.index')

@section('profile-content')
    @include('include.message')

    <form action="{{ route('profile.profile.update') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="i-name">{{ __('Name') }}</label>
            <input type="text" name="username" id="i-name"
                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                value="{{ old('username') ?? $user->username }}" placeholder="{{ __('Name') }}">
            @if ($errors->has('username'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="i-email">{{ __('Email') }}</label>
            <input type="text" name="email" id="i-email"
                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                value="{{ old('email') ?? $user->email }}" placeholder="{{ __('Email') }}">
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="row mt-3">
            <div class="col">
                <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </form>
@endsection
