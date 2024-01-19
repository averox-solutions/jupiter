@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}

<p>{{ __('Greetings! You can now host meetings') }}</p>

<ul>
<li><b>{{ __('Username') }}</b>: {{ $user['username'] }}</li>
<li><b>{{ __('Email') }}</b>: {{ $user['email'] }}</li>
<li><b>{{ __('Password') }}</b>: {{ $user['password'] }}</li>
</ul>

@component('mail::button', ['url' => Request::root() . '/login'])
{{ __('Login') }}
@endcomponent

<p>{{ __('Thank you') }}</p>

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
