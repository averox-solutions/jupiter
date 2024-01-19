<?php


namespace App\Traits;

use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\TaxRate;

trait PaymentTrait
{
    /**
     * Store the Payment.
     */
    private function paymentStore($params)
    {
        $payment = new Payment();
        $payment->user_id = $params['user_id'];
        $payment->plan_id = $params['plan_id'];
        $payment->payment_id = $params['payment_id'];
        $payment->gateway = $params['gateway'];
        $payment->amount = $params['amount'];
        $payment->currency = $params['currency'];
        $payment->interval = $params['interval'];
        $payment->status = $params['status'];
        $payment->product = Plan::select('id', 'name', 'currency', 'amount_' . $params['interval'])->where('id', '=', $params['plan_id'])->first();
        $payment->coupon = $params['coupon'] ? Coupon::select('id', 'name', 'code', 'type', 'percentage')->where('id', '=', $params['coupon'])->first() : null;
        $payment->tax_rates = $params['tax_rates'] ? TaxRate::select('id', 'name', 'type', 'percentage')->whereIn('id', explode('_', $params['tax_rates']))->get() : null;
        $payment->customer = $params['customer'];
        $payment->seller = collect([
            'title' => '',
            'vendor' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => '',
            'phone' => '',
            'vat_number' => ''
        ]);
        $payment->save();

        // Store the invoice ID
        $payment->invoice_id = $payment->id;
        $payment->save();

        return $payment;
    }
}