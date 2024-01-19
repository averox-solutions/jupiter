<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'name' => ['required', 'max:64'],
            'description' => ['required', 'max:256'],
            'amount_month' => ['required', 'numeric', 'min:0.00', 'max:9999999999'],
            'amount_year' => ['required', 'numeric', 'min:0.00', 'max:9999999999'],
            'currency' => ['required'],
            'coupons' => ['sometimes', 'nullable'],
            'tax_rates' => ['sometimes', 'nullable'],
            'features.time_limit' => ['required', 'integer'],
            'features.meeting_no' => ['required', 'integer'],
            'features.user_limit' => ['required', 'integer'],
            
            'features.text_chat' => ['required', 'integer', 'between:0,1'],
            'features.file_share' => ['required', 'integer', 'between:0,1'],
            'features.screen_share' => ['required', 'integer', 'between:0,1'],
            'features.whiteboard' => ['required', 'integer', 'between:0,1'],
            'features.hand_raise' => ['required', 'integer', 'between:0,1'],
            'features.recording' => ['required', 'integer', 'between:0,1']
        ];
    }
}
