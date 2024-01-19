@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page->title)

@section('style')
    <style>
        .card-body {
            min-height: 68vh;
        }
        .card-body a {
            color: #007bff !important;
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-10 mt-3 offset-md-1 text-justify">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0 text-bold">{{ $page->title }}</h4>
                    </div>
                    <div class="card-body">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
