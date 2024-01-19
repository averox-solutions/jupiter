@extends('profile.index')

@section('profile-content')
    @include('include.message')

    <div class="form-group">
        <form action="{{ route('profile.updateTfa') }}" method="post" id="js-tfa-form">
            @csrf
            <div class="form-group" style="margin-top:20px;">
                <label>{{ __('Two Factor Authentication') }}</label>
                <br>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="tfa" class="custom-control-input" id="js-tfa-switch"
                        {{ $user->tfa == 'active' ? 'checked' : '' }}>
                    <label class="custom-control-label"
                        for="js-tfa-switch">{{ $user->tfa == 'active' ? __('Enabled') : __('Disabled') }}</label>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //submit the form on switch toggle
            $(document).find('#js-tfa-switch').on('change', function() {
                $(this).is(":checked") ? $(this).val('active') : $(this).val('inactive');
                $('#js-tfa-form')[0].submit();
                $(this).attr('disabled', true);
            });
        });
    </script>
@endsection
