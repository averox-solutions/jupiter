@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.tax_rates.new') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="i-name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                value="{{ old('name') }}" placeholder="{{ __('Name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-percentage">{{ __('Percentage') }}</label>
                            <div class="input-group">
                                <input type="text" name="percentage" id="i-percentage"
                                    class="form-control{{ $errors->has('percentage') ? ' is-invalid' : '' }}"
                                    value="{{ old('percentage') }}" placeholder="{{ __('Percentage') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @if ($errors->has('percentage'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('percentage') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-type">{{ __('Type') }}</label>
                            <select name="type" id="i-type"
                                class="custom-select{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                @foreach ([0 => __('Inclusive'), 1 => __('Exclusive')] as $key => $value)
                                    <option value="{{ $key }}" @if (old('type') == $key) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('type'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-regions">{{ __('Regions') }}</label>
                            <select name="regions[]" id="i-regions"
                                class="custom-select{{ $errors->has('regions') ? ' is-invalid' : '' }}" multiple>
                                @foreach (config('countries') as $key => $value)
                                    <option value="{{ $key }}" @if (old('regions') !== null && in_array($key, old('regions'))) selected @endif>
                                        {{ __($value) }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('regions'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('regions') }}</strong>
                                </span>
                            @endif
                            <small id="regionsHelp"
                                class="form-text text-muted">{{ __('Leave empty to apply the tax rate on all regions') }}</small>
                        </div>
                    </div>
                </div>
                <button type="submit" id="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('admin.tax_rates') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
