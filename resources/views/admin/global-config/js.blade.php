@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('js.update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Custom JS') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('Add your custom JavaScript. Do add the script tag.') }}"></i>
                    </label>
                    <textarea class="form-control{{ $errors->has('CUSTOM_JS') ? ' is-invalid' : '' }}" name="CUSTOM_JS"
                        placeholder="{{ __('Custom JS') }}"
                        rows="6">{{ old('CUSTOM_JS') ?? getSetting('CUSTOM_JS') }}</textarea>
                    @if ($errors->has('CUSTOM_JS'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('CUSTOM_JS') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
