@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <div class="row">
                <a href="{{ route('createUser') }}"><button class="btn btn-primary btn-sm" id="createUser"
                        title="{{ __('Create User') }}">{{ __('Create') }}</button></a>
            </div>
            <br>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->username }}</td>
                            <td>{{ $value->email }}</td>
                            <td>{{ $value->created_at }}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input user-status"
                                        data-id="{{ $value->id }}" id="customSwitch{{ $value->id }}"
                                        {{ $value->status == 'active' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitch{{ $value->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-user" data-id="{{ $value->id }}"
                                    title="{{ __('Delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <div class="float-right">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
