<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateJsonFile;

class StoreLanguageRequest extends FormRequest
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
            'code' => 'required|string|unique:languages|max:10',
            'name' => 'required|string|max:50',
            'direction' => 'required|string|in:ltr,rtl',
            'default' => 'required|string|in:no,yes',
            'status' => 'required|string|in:active,inactive',
            'file' => ['required', 'file', 'max:1024', new ValidateJsonFile],
        ];
    }
}
