@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @include('include.message')
            
            <form action="{{route('updatePage', $model->id)}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Title') }}</label>
                            <input type="text" name="title" placeholder="{{ __('Title') }}" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                value="{{ $model->title }}" axlength="64" autofocus>
                            @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ __('Slug') }}</label>
                            <input type="text" name="slug" placeholder="{{ __('Slug') }}" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                            value="{{ $model->slug }}" maxlength="255" @if (in_array($model->slug, $restrictedSlugs)) readonly @endif>
                            @if ($errors->has('slug'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('slug') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Content') }}</label>
                            <textarea id="content" rows="6" name="content"
                                class="form-control {{ $errors->has('content') ? ' is-invalid' : '' }}">{{ $model->content }}</textarea>
                            @if ($errors->has('content'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="footer" id="footer" {{ $model->footer == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="footer">
                                {{ __('Show in footer') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" name="save" class="btn btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('pages') }}"><button type="button"
                        class="btn btn-default">{{ __('Back') }}</button></a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>

    <script>
        $(function() {
            CKEDITOR.replace('content', {
                toolbarGroups: [{
                        "name": "basicstyles",
                        "groups": ["basicstyles", "links"]
                    },
                    {
                        "name": 'paragraph',
                        "groups": ['list']
                    },
                    {
                        "name": "styles",
                        "groups": ["styles"]
                    },
                ],
                removeButtons: 'Styles,Font,FontSize,Superscript,Subscript,Strike,Anchor',
                language: '{{ getSelectedLanguage()->name }}'
            });
        });
    </script>
@endsection