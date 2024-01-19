@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <div class="row">
                <a href="{{ route('admin.plans.new') }}"><button class="btn btn-primary btn-sm" id="createCoupon"
                        title="{{ __('Create User') }}">{{ __('Create') }}</button></a>
            </div>
            <br>
            <table class="table table-bordered table-striped table-hover">
                <div class="d-flex">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Monthly amount') }}</th>
                            <th>{{ __('Yearly amount') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $key => $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                <td><a href="{{ route('admin.plans.edit', $value->id) }}"
                                        class="text-truncate">{{ $value->name }}</a>
                                    @if (!$value->hasPrice())
                                        <span class="badge badge-secondary">{{ __('Default') }}</span>
                                    @endif
                                </td>
                                <td>{{ $value->currency ? $value->currency : '-' }}</td>
                                <td>{{ $value->amount_month ? $value->amount_month : '-' }}</td>
                                <td>{{ $value->amount_year ? $value->amount_year : '-' }}</td>
                                <td>{{ $value->description }}</td>
                                <td>
                                    @if ($value->id == 1)
                                        -
                                    @else
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input plan-status"
                                                data-id="{{ $value->id }}" id="customSwitch{{ $value->id }}"
                                                {{ $value->status == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="customSwitch{{ $value->id }}"></label>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.plans.edit', $value->id) }}">
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
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Monthly amount') }}</th>
                            <th>{{ __('Yearly amount') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <div class="float-right">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
@endsection
