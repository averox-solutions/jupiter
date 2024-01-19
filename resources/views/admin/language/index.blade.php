@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <div class="row">
                <a href="{{ 'languages/add' }}"><button class="btn btn-primary btn-sm ml-1" id="createLanguage"
                        title="{{ __('Create Language') }}">{{ __('Create') }}</button></a>
                <a href="{{ 'languages/download-english' }}"><button class="btn btn-warning btn-sm ml-1"
                        title="{{ __('Download English File') }}">{{ __('Download English File') }}</button></a>
            </div>
            <br>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Direction') }}</th>
                        <th>{{ __('Default') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($languages as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->code }}</td>
                            <td>{{ $value->name }}</td>
                            <td>{{ strtoupper($value->direction) }}</td>
                            <td>
                                @if ($value->default == 'yes')
                                    <span class="badge badge-success">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ __('No') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($value->status == 'active')
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="/languages/edit/{{ $value->id }}">
                                    <button class="btn btn-primary btn-sm" title="{{ __('Edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </a>
                                <button class="btn btn-danger btn-sm deleteLanguage" data-id="{{ $value->id }}"
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
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Direction') }}</th>
                        <th>{{ __('Default') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <div class="float-right">
                {{ $languages->links() }}
            </div>
        </div>
    </div>
@endsection
