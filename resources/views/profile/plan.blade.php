@extends('profile.index')

@section('profile-content')
    @include('include.message')
    
    <form action="{{ route('profile.plan') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <div class="text-muted">{{ __('Plan') }}</div>
                <div>{{ $user->plan->name }}</div>
            </div>

            @if (!$user->planOnDefault())
                @if ($user->plan_payment_gateway)
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Gateway') }}</div>
                        <div>{!! ucwords($user->plan_payment_gateway) !!}</div>
                    </div>
                @endif

                @if ($user->plan_amount && $user->plan_currency && $user->plan_interval)
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Amount') }}</div>
                        <div>{{ formatMoney($user->plan_amount, $user->plan_currency) }} {{ $user->plan_currency }} /
                            <span
                                class="text-lowercase">{{ $user->plan_interval == 'month' ? __('Month') : __('Year') }}</span>
                        </div>
                    </div>
                @endif

                @if ($user->plan_recurring_at)
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Recurring at') }}</div>
                        <div>{{ $user->plan_recurring_at }}</div>
                    </div>
                @endif

                @if ($user->plan_trial_ends_at && $user->plan_trial_ends_at->gt(Carbon\Carbon::now()))
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Trial ends at') }}</div>
                        <div>{{ $user->plan_trial_ends_at }}</div>
                    </div>
                @endif

                @if ($user->plan_ends_at)
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Ends at') }}</div>
                        <div>{{ $user->plan_ends_at }}</div>
                    </div>
                @endif
            @endif
        </div>

        @if ($user->plan_recurring_at)
            <button type="button" class="btn btn-outline-danger mb-3" data-toggle="modal"
                data-target="#cancel-modal">{{ __('Cancel') }}</button>
        @endif

        @if (Auth::user()->role != 'admin')
            <a href="{{ route('pricing') }}">
                <button type="button" name="submit" class="btn btn-primary mb-3">{{ __('Upgrade') }}</button>
            </a>
        @endif
    </form>

    <div class="modal fade" id="cancel-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="cancel-modal-label">{{ __('Cancel') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center width-12 height-14"
                        data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        {{ __('You\'ll continue to have access to the features you\'ve paid for until the end of your billing cycle.') }}
                    </div>
                    <div>{{ __('Are you sure you want to cancel :name?', ['name' => $user->plan->name]) }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <form action="{{ route('cancelPlan') }}" method="post">

                        @csrf

                        <button type="submit" class="btn btn-danger">{{ __('Cancel') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
