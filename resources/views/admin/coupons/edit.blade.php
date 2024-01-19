@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.edit', $coupon->id) }}" method="post" id="form-coupon">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-type">{{ __('Type') }}</label>
                            <select name="type" id="i-type"
                                class="custom-select{{ $errors->has('type') ? ' is-invalid' : '' }}" disabled>
                                @foreach ([0 => __('Discount'), 1 => __('Redeemable')] as $key => $value)
                                    <option value="{{ $key }}" @if ((old('type') !== null && old('type') == $key) || ($coupon->type == $key && old('type') == null)) selected @endif>
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
                            <label for="i-name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="i-name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                value="{{ old('name') ?? $coupon->name }}" placeholder="{{ __('Name') }}" disabled>
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
                            <label for="i-code">{{ __('Code') }}</label>
                            <div class="input-group">
                                <input type="text" name="code" id="i-code"
                                    class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"
                                    value="{{ old('code') ?? $coupon->code }}" placeholder="{{ __('Code') }}">
                                <div class="input-group-append">
                                    <div class="btn btn-primary" id="coupon_copy">{{ __('Copy') }}</div>
                                </div>
                            </div>
                            @if ($errors->has('code'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div
                        class="col-sm-6 {{ (old('type') == 0 && old('type') !== null) || (old('type') == null && $coupon->type == 0) ? '' : 'd-none' }}">
                        <div class="form-group {{ (old('type') == 0 && old('type') !== null) || (old('type') == null && $coupon->type == 0) ? '' : 'd-none' }}"
                            id="form-group-discount">
                            <label for="i-percentage">{{ __('Percentage off') }}</label>
                            <div class="input-group">
                                <input type="text" name="percentage" id="i-percentage"
                                    class="form-control{{ $errors->has('percentage') ? ' is-invalid' : '' }}"
                                    value="{{ old('percentage') ?? $coupon->percentage }}"
                                    placeholder="{{ __('Percentage off') }}" disabled>
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

                    <div class="col-sm-6">
                        <div class="form-group {{ old('type') == 1 || (old('type') == null && $coupon->type == 1) ? '' : 'd-none' }}"
                            id="form-group-redeemable">
                            <label for="i-days">{{ __('Days') }}</label>
                            <input type="number" name="days" id="i-days"
                                class="form-control{{ $errors->has('days') ? ' is-invalid' : '' }}"
                                value="{{ old('days') ?? $coupon->days }}" placeholder="{{ __('Days') }}">
                            @if ($errors->has('days'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('days') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="i-quantity">{{ __('Quantity') }}</label>
                            <input type="text" name="quantity" id="i-quantity"
                                class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                value="{{ old('quantity') ?? $coupon->quantity }}" placeholder="{{ __('Quantity') }}">
                            @if ($errors->has('quantity'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('quantity') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('admin.coupons') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
