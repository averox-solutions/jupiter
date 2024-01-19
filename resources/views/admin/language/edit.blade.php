@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="">
                <label>{{ $model->name . ' (' . $model->code . ')' }}</label>
                <a href="{{ '/languages/download-file/' . $model->code }}" class="float-right"><button
                        class="btn btn-warning btn-sm ml-1"
                        title="{{ __('Download File') }}">{{ __('Download File') }}</button></a>
            </div>
            <hr>
            @include('include.message')

            <form action="{{ route('updateLanguage', $model->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Direction') }}</label>
                            <select name="direction"
                                class="form-control{{ $errors->has('direction') ? ' is-invalid' : '' }}">
                                <option value="ltr" @if ($model->direction == 'ltr') selected @endif>{{ __('LTR') }}
                                </option>
                                <option value="rtl" @if ($model->direction == 'rtl') selected @endif>{{ __('RTL') }}
                                </option>
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
                                <option value="no" @if ($model->default == 'no') selected @endif>{{ __('No') }}
                                </option>
                                <option value="yes" @if ($model->default == 'yes') selected @endif>{{ __('Yes') }}
                                </option>
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
                                <option value="active" @if ($model->status == 'active') selected @endif>
                                    {{ __('Active') }}</option>
                                <option value="inactive" @if ($model->status == 'inactive') selected @endif>
                                    {{ __('Inactive') }}</option>
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

                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('languages') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
                <input type="hidden" name="id" value="{{ $model->id }}">
            </form>
        </div>
    </div>
@endsection
