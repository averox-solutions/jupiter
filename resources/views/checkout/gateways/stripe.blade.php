@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ')

@section('style')
    <style>
        .container a {
            color: #fff !important;
        }
    </style>
@endsection

@section('content')
@endsection

@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ getSetting("STRIPE_KEY") }}');
        stripe.redirectToCheckout({
            sessionId: '{{ $stripeSession->id }}'
        });
    </script>
@endsection