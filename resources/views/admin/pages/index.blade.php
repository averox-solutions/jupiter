@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            <div class="row">
                <a href="{{ 'pages/add' }}"><button class="btn btn-primary btn-sm ml-1" id="createLanguage"
                        title="{{ __('Create Language') }}">{{ __('Create') }}</button></a>
            </div>
            <br>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Show in footer') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->title }}</td>
                            <td>{{ $value->slug }}</td>
                            <td>{{ ucfirst($value->footer) }}</td>
                            <td>
                                <a href="/pages/edit/{{ $value->id }}">
                                    <button class="btn btn-primary btn-sm" title="{{ __('Edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </a>
                                <button class="btn btn-danger btn-sm deletePage" data-id="{{ $value->id }}"
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
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Show in footer') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <div class="float-right">
                {{ $pages->links() }}
            </div>
        </div>
    </div>
@endsection
