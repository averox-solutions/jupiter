@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            @if (getSetting('PAYMENT_MODE') == 'disabled')
                <span
                    class="badge badge-warning p-2 mb-3">{{ __('The payment mode is disabled, enable now to make the features paid') }}</span>
            @endif
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Plan') }}</th>
                        <th>{{ __('Coupon') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Gateway') }}</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Transaction Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction as $key => $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->user->username }}</td>
                            <td>{{ $value->plan->name }}</td>
                            <td>{{ $value->coupon ? $value->coupon->name : '-' }}</td>
                            <td>{{ $value->amount }}</td>
                            <td>{{ $value->currency }}</td>
                            <td>{{ $value->interval }}</td>
                            <td>{{ $value->gateway }}</td>
                            <td>{{ $value->payment_id }}</td>
                            <td>{{ $value->status }}</td>
                            <td>{{ $value->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Plan') }}</th>
                        <th>{{ __('Coupon') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Gateway') }}</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Transaction Date') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <div class="float-right">
                {{ $transaction->links() }}
            </div>
        </div>
    </div>
@endsection
