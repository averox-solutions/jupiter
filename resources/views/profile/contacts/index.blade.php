@extends('profile.index')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('profile-content')
    @include('include.message')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <a href="{{ route('profile.createContactForm') }}"><button class="btn btn-primary btn-sm"
                        id="createContact" title="{{ __('Create Contact') }}">{{ __('Create') }}</button></a>
                <a href="{{ route('profile.importContactForm') }}" style="margin-left:5px;"><button
                        class="btn btn-success btn-sm" id="createContact"
                        title="{{ __('Create Contact') }}">{{ __('Import') }}</button></a>
            </div>
            <br>
            @if (count($contacts))
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th>{{ __('Updated') }}</th>
                            <th>{{ __('Edit') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $key => $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->created_at->diffForHumans() }}</td>
                                <td>{{ $value->updated_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('profile.editContactForm', $value->id) }}">
                                        <button class="btn btn-primary btn-sm edit-contact"
                                            data-id="{{ $value->id }}" title="{{ __('Edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-contact"
                                        data-url="{{ route('profile.deleteContact') }}" data-id="{{ $value->id }}"
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
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th>{{ __('Updated') }}</th>
                            <th>{{ __('Edit') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="card-footer">
                    <div class="float-right">
                        {{ $contacts->links() }}
                    </div>
                </div>
            @else
                <p>{{ __('Your contacts will appear here') }}</p>
            @endif
        </div>
    </div>
@endsection
