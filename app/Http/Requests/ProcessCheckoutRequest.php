<?php

namespace App\Http\Requests;

use App\Rules\ValidateCouponCodeRule;
use App\Rules\ValidateReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class ProcessCheckoutRequest extends FormRequest
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
            'payment_gateway' => ['required'],
            'interval' => ['required', 'in:month,year'],
            'name' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'postal_code' => ['required'],
            'country' => ['required'],
            'coupon' => ['nullable', 'min:1', new ValidateCouponCodeRule($this->route('id'))],
            'g-recaptcha-response' => [new RequiredIf(getSetting('GOOGLE_RECAPTCHA') == 'enabled'), new ValidateReCaptcha]
        ];
    }

    //set custom validation messages
    public function messages()
    {
        return [
            'g-recaptcha-response.required' => __('Please check this option')
        ];
    }
}
