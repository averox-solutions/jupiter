<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingPaymentGatewaysRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'STRIPE' => ['required', 'integer', 'between:0,1'],
            'STRIPE_KEY' => ['required_if:STRIPE,1'],
            'STRIPE_SECRET' => ['required_if:STRIPE,1'],
            'STRIPE_WH_SECRET' => ['required_if:STRIPE,1'],
            
            'PAYPAL' => ['required', 'integer', 'between:0,1'],
            'PAYPAL_MODE' => ['required_if:PAYPAL,1'],
            'PAYPAL_CLIENT_ID' => ['required_if:PAYPAL,1'],
            'PAYPAL_SECRET' => ['required_if:PAYPAL,1'],
            'PAYPAL_WEBHOOK_ID' => ['required_if:PAYPAL,1'],            
        ];
    }

    /**
     * Get the validation messages that apply to the request.
    *
    * @return array
    */
    public function messages()
    {
    // use trans instead on Lang 
        return [
            'STRIPE_KEY.required_if' => __("Stripe publishable key is required."),
            'STRIPE_SECRET.required_if' => __("Stripe secret key is required."),
            'STRIPE_WH_SECRET.required_if' => __("Stripe signing secret is required."),

            'PAYPAL_CLIENT_ID.required_if' => __("PayPal client ID is required."),
            'PAYPAL_SECRET.required_if' => __("PayPal secret key is required."),
            'PAYPAL_WEBHOOK_ID.required_if' => __("PayPal webhook ID is required."),
        ];
    }
}
