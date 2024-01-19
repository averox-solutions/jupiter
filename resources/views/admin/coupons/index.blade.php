@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
<div class="card">
    <div class="card-body">
        @include('include.message')
        <div class="row">
            <a href="{{ route('admin.coupons.new') }}"><button class="btn btn-primary btn-sm" id="createCoupon"
                    title="{{ __('Create User') }}">{{ __('Create') }}</button></a>
        </div>
        <br>
        <table class="table table-bordered table-striped table-hover"><div class="d-flex">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Redeems') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $key => $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->code }}</td>
                        <td>{{ $value->quantity }}</td>
                        <td>{{ $value->redeems }}</td>
                        <td>{{ number_format($value->percentage, 2, __('.'), __(',')) }}%<span class="text-muted">{{ ($value->type ? __('Redeemable') : __('Discount')) }}</span></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input coupon-status"
                                    data-id="{{ $value->id }}" id="customSwitch{{ $value->id }}"
                                    {{ $value->status == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customSwitch{{ $value->id }}"></label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.coupons.edit', $value->id) }}">
                                <button class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Redeems') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card-footer">
        <div class="float-right">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection