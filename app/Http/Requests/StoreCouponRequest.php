<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
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
            'name' => ['required', 'max:128'],
            'code' => ['required', 'alpha_dash', 'max:128', 'unique:coupons,code'],
            'type' => ['required', 'integer', 'between:0,1'],
            'days' => ['required_if:type,1', 'nullable', 'integer', 'min:-1', 'max:3650'],
            'percentage' => ['required_if:type,0', 'nullable', 'numeric', 'min:1', 'max:99.99'],
            'quantity' => ['required', 'integer'],
        ];
    }
}
