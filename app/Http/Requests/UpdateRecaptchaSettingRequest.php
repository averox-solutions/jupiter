<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecaptchaSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'GOOGLE_RECAPTCHA' => 'required|string|in:enabled,disabled',
            'GOOGLE_RECAPTCHA_KEY' => 'required|string|max:100',
            'GOOGLE_RECAPTCHA_SECRET' => 'required|string|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'GOOGLE_RECAPTCHA' => __('Google reCAPTCHA'),
            'GOOGLE_RECAPTCHA_KEY' => __('Google reCAPTCHA Key'),
            'GOOGLE_RECAPTCHA_SECRET' => __('Google reCAPTCHA Secret'),
        ];
    }
}
