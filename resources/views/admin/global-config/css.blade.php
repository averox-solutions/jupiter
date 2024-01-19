@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('css.update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('Custom CSS') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('Add your custom CSS. Do NOT add the style tag.') }}"></i>
                    </label>
                    <textarea class="form-control{{ $errors->has('CUSTOM_CSS') ? ' is-invalid' : '' }}" name="CUSTOM_CSS"
                        placeholder="{{ __('Custom CSS') }}"
                        rows="6">{{ old('CUSTOM_CSS') ?? getSetting('CUSTOM_CSS') }}</textarea>
                    @if ($errors->has('CUSTOM_CSS'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('CUSTOM_CSS') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
