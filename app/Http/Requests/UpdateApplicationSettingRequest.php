<?php

namespace App\Http\Requests;

use App\Rules\ValidateApplication;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationSettingRequest extends FormRequest
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
            'AUTH_MODE' => 'required|string|in:enabled,disabled',
            'COOKIE_CONSENT' => 'required|string|in:enabled,disabled',
            'LANDING_PAGE' => 'required|string|in:enabled,disabled',
            'SOCIAL_INVITATION' => 'required|string|max:255',
            'GOOGLE_ANALYTICS_ID' => 'required|string|max:20',
            'PAYMENT_MODE' => ['required', 'string', 'in:enabled,disabled', new ValidateApplication],
            'REGISTRATION' => 'required|string|in:enabled,disabled',
            'VERIFY_USERS' => 'required|string|in:enabled,disabled',
        ];
    }

    public function attributes()
    {
        return [
            'AUTH_MODE' => __('Auth Mode'),
            'COOKIE_CONSENT' => __('Cookie Consent'),
            'LANDING_PAGE' => __('Landing page'),
            'GOOGLE_ANALYTICS_ID' => __('Google analytics ID'),
            'SOCIAL_INVITATION' => __('Social Invitation'),
            'PAYMENT_MODE' => __('Payment Mode'),
            'REGISTRATION' => __('Registration'),
            'VERIFY_USERS' => __('Verify Users'),
        ];
    }

}
