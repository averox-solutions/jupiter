@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('style')
    <style>
        .flex-fill {
            padding-bottom: 1.5rem;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
        }

    </style>
@endsection

@section('content')
    <div class="flex-fill">
        <div class="bg-base-1">
            <div class="container py-6">
                @include('include.message')
                <div class="row align-items-center">
                    <div class="col-12 col-md-5 offset-md-1 text-center text-md-left">
                        <h2 class="mb-3 d-inline-block title-bold">{{ __('Simple, transparent pricing') }}</h2>
                        <div class="m-auto">
                            <p class="text-muted font-weight-normal font-size-lg">
                                {{ __('Choose a plan that works best for you') }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 col-md-offset-1 text-center text-md-right">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary active" id="plan-month">
                                <input type="radio" name="options" autocomplete="off" checked>{{ __('Monthly') }}
                            </label>
                            <label class="btn btn-outline-primary" id="plan-year">
                                <input type="radio" name="options" autocomplete="off">{{ __('Yearly') }}
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row flex-column-reverse flex-md-row justify-content-center">
                    @foreach ($plans as $plan)
                        <div class="col-12 col-md-4 pt-4">
                            <div class="card border-0 shadow-sm rounded h-100 overflow-hidden plan text-center">
                                <div class="card-header">
                                    <h5>{{ $plan->name }}</h5>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    @if ($plan->amount_month * 12 > $plan->amount_year)
                                        <div class="plan-year d-none">
                                            <div class="ribbon-wrapper ribbon-lg">
                                                <div class="ribbon bg-primary">
                                                    {{ __('Save :value%', ['value' => number_format((($plan->amount_month * 12 - $plan->amount_year) / ($plan->amount_month * 12)) * 100, 0)]) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="my-2">
                                        @if ($plan->hasPrice())
                                            <div class="plan-preload plan-month d-none d-block">
                                                <div class="h1 mb-1">
                                                    <span class="font-weight-bold">
                                                        {{ formatMoney($plan->amount_month, $plan->currency) }}
                                                    </span>
                                                    <span class="pricing-plan-price text-muted">
                                                        {{ $plan->currency }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="plan-year d-none">
                                                <div class="h1 mb-1">
                                                    <span class="font-weight-bold">
                                                        {{ formatMoney($plan->amount_year, $plan->currency) }}
                                                    </span>
                                                    <span class="pricing-plan-price text-muted">
                                                        {{ $plan->currency }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="plan-preload plan-month d-none d-block">
                                                <h1 class="mb-1">
                                                    <span class="font-weight-bold text-uppercase">
                                                        {{ __('Free') }}
                                                    </span>
                                                </h1>
                                            </div>

                                            <div class="plan-year d-none">
                                                <h1 class="mb-1">
                                                    <span class="font-weight-bold text-uppercase">
                                                        {{ __('Free') }}
                                                    </span>
                                                </h1>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                {{ __('Audio Chat') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                {{ __('Video Chat') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span
                                                    class="@if (!$plan->features->text_chat) no-feature @endif">{{ __('Text Chat') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span
                                                    class="@if (!$plan->features->screen_share) no-feature @endif">{{ __('Screen Sharing') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span
                                                    class="@if (!$plan->features->file_share) no-feature @endif">{{ __('File Sharing') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span
                                                    class="@if (!$plan->features->whiteboard) no-feature @endif">{{ __('Whiteboard') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span
                                                    class="@if (!$plan->features->hand_raise) no-feature @endif">{{ __('Hand Raise') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                <span
                                                    class="@if (!$plan->features->recording) no-feature @endif">{{ __('Recording') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                {!! '<b>' . ($plan->features->time_limit == -1 ? __('Unlimited') : __('Up to') . $plan->features->time_limit) . '</b>' . __('minutes') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                {!! '<b>' . ($plan->features->meeting_no == -1 ? __('Unlimited') : $plan->features->meeting_no) . '</b> ' . __('Meetings') !!}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="row py-2">
                                            <div class="col">
                                                {!! '<b>' . ($plan->features->user_limit == -1 ? __('Unlimited') : $plan->features->user_limit) . '</b> ' . __('Users') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="plan-footer d-flex align-items-end mt-auto">
                                        <div class="z-1 w-100">
                                            @auth
                                                @if ($plan->hasPrice() && count(paymentGateways()) != 0)
                                                    @if (isset(Auth::user()->plan->id) && Auth::user()->plan->id == $plan->id && Auth::user()->plan_recurring_at != null)
                                                        <div class="btn btn-primary btn-block text-uppercase py-2 disabled">
                                                            {{ __('Active') }}</div>
                                                    @else
                                                        <div class="plan-no-animation plan-month d-none d-block">
                                                            <a href="{{ route('checkout.index', ['id' => $plan->id, 'interval' => 'month']) }}"
                                                                class="btn btn-primary btn-block text-uppercase py-2">
                                                                {{ __('Subscribe') }}
                                                            </a>
                                                        </div>
                                                        <div class="plan-no-animation plan-year d-none">
                                                            <a href="{{ route('checkout.index', ['id' => $plan->id, 'interval' => 'year']) }}"
                                                                class="btn btn-primary btn-block text-uppercase py-2">
                                                                {{ __('Subscribe') }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="btn btn-primary btn-block text-uppercase py-2 disabled">
                                                        {{ __('Free') }}
                                                    </div>
                                                @endif
                                            @else
                                                <div class="plan-no-animation plan-month d-none d-block">
                                                    <a href="{{ route('register', ['plan' => $plan->id, 'interval' => 'month']) }}"
                                                        class="btn btn-primary btn-block text-uppercase py-2">{{ __('Register') }}</a>
                                                </div>
                                                <div class="plan-no-animation plan-year d-none">
                                                    <a href="{{ route('register', ['plan' => $plan->id, 'interval' => 'year']) }}"
                                                        class="btn btn-primary btn-block text-uppercase py-2">{{ __('Register') }}</a>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
