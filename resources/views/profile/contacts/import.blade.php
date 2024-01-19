@extends('profile.index')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('profile-content')
    @include('include.message')
    <div class="card">
        <div class="card-body">
            <form id="importContact" action="{{ route('profile.importContact') }}" method="post" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <a href="{{ route('profile.downloadCsvFile') }}">
                                <button type="button" class="btn btn-warning btn-sm">{{ __('Download Sample File') }}</button>
                            </a>
                            <hr>
                            <label>{{ __('CSV File') }}</label>
                            <input type='file' name='file' accept=".csv" required>
                        </div>
                    </div>
                </div>

                <button type="submit" id="save" class="btn btn-primary">{{ __('Import') }}</button>
                <a href="{{ route('profile.contacts') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection
