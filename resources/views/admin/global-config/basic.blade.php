@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('basic.update') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Application Name') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('Application Name will be visible in the entire application.') }}"></i>
                    </label>
                    <input type="text" name="APPLICATION_NAME"
                        class="form-control{{ $errors->has('APPLICATION_NAME') ? ' is-invalid' : '' }}"
                        value="{{ old('APPLICATION_NAME') ?? getSetting('APPLICATION_NAME') }}"
                        placeholder="{{ __('Application Name') }}">
                    @if ($errors->has('APPLICATION_NAME'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('APPLICATION_NAME') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Primary Color') }}
                        <i class="fa fa-info-circle info" title="{{ __('Set the primary color for the front-end.') }}"></i>
                    </label>
                    <input type="color" name="PRIMARY_COLOR"
                        class="form-control{{ $errors->has('PRIMARY_COLOR') ? ' is-invalid' : '' }}"
                        value="{{ old('PRIMARY_COLOR') ?? getSetting('PRIMARY_COLOR') }}">
                    @if ($errors->has('PRIMARY_COLOR'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('PRIMARY_COLOR') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Primary Logo') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This will be the main logo. Only PNG is supported. The maximum allowed size is 2 MB.') }}"></i>
                    </label>
                    <input type="file" name="PRIMARY_LOGO" accept=".png"
                        class="form-control{{ $errors->has('PRIMARY_LOGO') ? ' is-invalid' : '' }}"
                        value="{{ old('PRIMARY_LOGO') ?? getSetting('PRIMARY_LOGO') }}">
                    @if ($errors->has('PRIMARY_LOGO'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('PRIMARY_LOGO') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Secondary Logo') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This will visible during the video meeting and in the admin panel. Only PNG is supported. The maximum allowed size is 2 MB.') }}"></i>
                    </label>
                    <input type="file" name="SECONDARY_LOGO" accept=".png"
                        class="form-control{{ $errors->has('SECONDARY_LOGO') ? ' is-invalid' : '' }}"
                        value="{{ old('SECONDARY_LOGO') ?? getSetting('SECONDARY_LOGO') }}">
                    @if ($errors->has('SECONDARY_LOGO'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('SECONDARY_LOGO') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Favicon Icon') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This will be the favicon. Only PNG is supported. The maximum allowed size is 2 MB.') }}"></i>
                    </label>
                    <input type="file" name="FAVICON" accept=".png"
                        class="form-control{{ $errors->has('FAVICON') ? ' is-invalid' : '' }}"
                        value="{{ old('FAVICON') ?? getSetting('FAVICON') }}">
                    @if ($errors->has('FAVICON'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('FAVICON') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
