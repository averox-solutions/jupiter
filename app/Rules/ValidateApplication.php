<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateApplication implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value == 'enabled') {
            $license_notifications_array = aplVerifyLicense('', true);

            if ($license_notifications_array['notification_case'] == "notification_license_ok" && json_decode($license_notifications_array['notification_data'])->type == "Extended License") {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You will need an Extended License to activate the payment module');
    }
}
