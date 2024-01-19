@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('storeUser') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Username') }}</label>
                            <input type="text" name="username" placeholder="{{ __('Username') }}"
                                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                value="{{ old('username') }}" maxlength="20" autofocus>
                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Email') }}</label>
                            <input type="email" name="email" placeholder="{{ __('Email') }}"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ old('email') }}" maxlength="50">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Password') }}</label>
                            <div id="passwordContainer">
                                <input type="password" name="password" placeholder="{{ __('Password') }}"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    class="form-control" value="{{ old('password') }}" maxlength="50">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <button type="button" id="togglePassword" class="btn btn-info btn-sm ml-1"><i
                                        class="fa fa-eye"></i></button>
                                <button type="button" id="generateRandomPassword" class="btn btn-warning btn-sm ml-1"><i
                                        class="fa fa-random"></i></button>
                            </div>
                            <b>{{ __('Note: An email will be sent to the user') }}</b>
                        </div>
                    </div>
                </div>

                <button type="submit" id="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('users') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#generateRandomPassword").trigger('click');
    </script>
@endsection
