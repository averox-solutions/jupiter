@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify your code') }}</div>
                    <div class="card-body">
                        @include('include.message')

                        <form method="POST" action="{{ route('tfa.post') }}">
                            @csrf
                            <p class="text-center">{{ __('The code has been sent to') }} :
                                {{ substr(auth()->user()->email, 0, 5) . '******' . substr(auth()->user()->email, -2) }}
                            </p>

                            <div class="form-group row">
                                <label for="code"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>
                                <div class="col-md-6">
                                    <input id="code" type="number"
                                        class="form-control @error('code') is-invalid @enderror" name="code"
                                        value="{{ old('code') }}" required autocomplete="code" autofocus>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a class="btn btn-link resend-code disabled"
                                        href="{{ route('tfa.resend') }}">{!! __('Resend Code (in :seconds seconds)', ['seconds' => '<span id="seconds">30</span>']) !!}</a>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //enable resend link
            let seconds = $("#seconds").text();

            const timer = setInterval(() => {
                $("#seconds").text(--seconds);

                if (!seconds) {
                    $(".resend-code").removeClass('disabled');
                    clearInterval(timer);
                }
            }, 1000);
        });
    </script>
@endsection
