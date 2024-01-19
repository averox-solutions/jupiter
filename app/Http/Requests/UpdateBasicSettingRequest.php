<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicSettingRequest extends FormRequest
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
            'APPLICATION_NAME' => 'required|string|max:20',
            'PRIMARY_COLOR' => 'required|string|max:7',
            'PRIMARY_LOGO' => 'nullable|file|mimes:png|max:2048',
            'SECONDARY_LOGO' => 'nullable|file|mimes:png|max:2048',
            'FAVICON' => 'nullable|file|mimes:png|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'APPLICATION_NAME' => __('Application Name'),
            'PRIMARY_COLOR' => __('Primary Color'),
            'PRIMARY_LOGO' => __('Primary Logo'),
            'SECONDARY_LOGO' => __('Secondary Logo'),
            'FAVICON' => __('Favicon Icon'),
        ];
    }
}
