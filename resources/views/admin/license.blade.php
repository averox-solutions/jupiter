@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <button id="verifyLicense" class="btn btn-primary">{{ __('Verify License') }}</button>
                        <button id="uninstallLicense" class="btn btn-danger">{{ __('Uninstall License') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
