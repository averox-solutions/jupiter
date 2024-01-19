@extends('profile.index')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('profile-content')
    @include('include.message')
    <div class="card">
        <div class="card-body">
            <form id="createContact" action="{{ route('profile.createContact') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" maxlength="20" autofocus
                                required>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Email') }}</label>
                            <input type="email" name="email" placeholder="{{ __('Email') }}"
                                value="{{ old('email') }}"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" maxlength="50"
                                required>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" id="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('profile.contacts') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
