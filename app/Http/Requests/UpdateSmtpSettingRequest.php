<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmtpSettingRequest extends FormRequest
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
            'MAIL_MAILER' => 'required|string|max:100',
            'MAIL_HOST' => 'required|string|max:100',
            'MAIL_PORT' => 'required|string|max:100',
            'MAIL_FROM_ADDRESS' => 'required|string|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'MAIL_MAILER' => __('Mailer'),
            'MAIL_HOST' => __('Host'),
            'MAIL_PORT' => __('Port'),
            'MAIL_USERNAME' => __('Username'),
            'MAIL_PASSWORD' => __('Password'),
            'MAIL_ENCRYPTION' => __('Encryption'),
            'MAIL_FROM_ADDRESS' => __('From Address'),
        ];
    }
}
