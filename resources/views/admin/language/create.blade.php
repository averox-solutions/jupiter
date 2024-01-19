@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <form action="{{ route('createLanguage') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Code') }}</label>
                            <input type="text" name="code" placeholder="{{ __('Code') }}"
                                class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"
                                value="{{ old('code') }}" maxlength="64" autofocus>
                            @if ($errors->has('code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" placeholder="{{ __('Name') }}"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                value="{{ old('name') }}" maxlength="255">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Direction') }}</label>
                            <select name="direction"
                                class="form-control{{ $errors->has('direction') ? ' is-invalid' : '' }}">
                                <option value="ltr">{{ __('LTR') }}</option>
                                <option value="rtl">{{ __('RTL') }}</option>
                            </select>
                            @if ($errors->has('direction'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('direction') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Default') }}</label>
                            <select name="default"
                                class="form-control{{ $errors->has('default') ? ' is-invalid' : '' }}">
                                <option value="no">{{ __('No') }}</option>
                                <option value="yes">{{ __('Yes') }}</option>
                            </select>
                            @if ($errors->has('default'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('default') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select name="status" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}">
                                <option value="active">{{ __('Active') }}</option>
                                <option value="inactive">{{ __('Inactive') }}</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('File') }}</label>
                            <input type="file" name="file"
                                class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" accept=".json">
                            @if ($errors->has('file'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('file') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" name="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('languages') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
