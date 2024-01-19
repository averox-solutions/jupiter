<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingSettingRequest extends FormRequest
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
            'SIGNALING_URL' => 'required|url|max:50',
            'MODERATOR_RIGHTS' => 'required|string|in:enabled,disabled',
            'DEFAULT_USERNAME' => 'required|string|max:15',
            'END_URL' => 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'SIGNALING_URL' => __('Signaling URL'),
            'MODERATOR_RIGHTS' => __('Moderator Rights'),
            'DEFAULT_USERNAME' => __('Default Username'),
            'END_URL' => __('End URL'),
        ];
    }
}
