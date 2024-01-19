@extends('admin.global-config.index')

@section('global-config-content')
    <form action="{{ route('meeting.update') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Signaling URL') }}
                        <i class="fa fa-info-circle info" title="{{ __('Signaling server (NodeJS) URL.') }}"></i>
                    </label>
                    <input type="text" name="SIGNALING_URL"
                        class="form-control{{ $errors->has('SIGNALING_URL') ? ' is-invalid' : '' }}"
                        value="{{ old('SIGNALING_URL') ?? getSetting('SIGNALING_URL') }}"
                        placeholder="{{ __('Signaling URL') }}">
                    @if ($errors->has('SIGNALING_URL'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('SIGNALING_URL') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Default Username') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('This will be the default username when a guest user joins the meeting without entering his name.') }}"></i>
                    </label>
                    <input type="text" name="DEFAULT_USERNAME"
                        class="form-control{{ $errors->has('DEFAULT_USERNAME') ? ' is-invalid' : '' }}"
                        value="{{ old('DEFAULT_USERNAME') ?? getSetting('DEFAULT_USERNAME') }}"
                        placeholder="{{ __('Default Username') }}">
                    @if ($errors->has('DEFAULT_USERNAME'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('DEFAULT_USERNAME') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('Moderator Rights') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('If on, the moderator will be able to accept/reject requests to join the room and can kick the users out of the room.') }}"></i>
                    </label>
                    <select name="MODERATOR_RIGHTS"
                        class="form-control{{ $errors->has('MODERATOR_RIGHTS') ? ' is-invalid' : '' }}">
                        <option value="enabled" @if (old('MODERATOR_RIGHTS') ? old('MODERATOR_RIGHTS') == "enabled" : getSetting('MODERATOR_RIGHTS') == 'enabled') selected @endif>{{ __('On') }}
                        </option>
                        <option value="disabled" @if (old('MODERATOR_RIGHTS') ? old('MODERATOR_RIGHTS') == "disabled" : getSetting('MODERATOR_RIGHTS') == 'disabled') selected @endif>{{ __('Off') }}
                        </option>
                    </select>
                    @if ($errors->has('MODERATOR_RIGHTS'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('MODERATOR_RIGHTS') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{ __('End URL') }}
                        <i class="fa fa-info-circle info"
                            title="{{ __('A web page to display when the meeting is over. Enter a URL. Leave \'null\' to reload the page. Set custom page like this: /pages/thank-you') }}"></i>
                    </label>
                    <input type="text" name="END_URL"
                        class="form-control{{ $errors->has('END_URL') ? ' is-invalid' : '' }}"
                        value="{{ old('END_URL') ?? getSetting('END_URL') }}"
                        placeholder="{{ __('End URL') }}">
                    @if ($errors->has('END_URL'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('END_URL') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>
@endsection
