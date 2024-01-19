<?php

namespace App\Rules;

use App\Models\Coupon;
use App\Models\Plan;
use Illuminate\Contracts\Validation\Rule;

class ValidateCouponCodeRule implements Rule
{
    /**
     * The plan id.
     *
     * @var
     */
    private $plan;

    /**
     * The error message.
     *
     * @var
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @param $link
     * @return void
     */
    public function __construct($plan)
    {
        $this->plan = $plan;
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
        $coupon = Coupon::where('code', '=', $value)->where('status', 1)->first();

        // If the coupon exists, and there's still quantity left
        if ($coupon && $coupon->quantity > $coupon->redeems) {
            $plan = Plan::where('id', '=', $this->plan)->where('status', 1)->priced()->firstOrFail();

            // If the plan has coupons on it, and the coupon is enabled on the plan
            if ($plan->coupons && in_array($coupon->id, $plan->coupons)) {
                return true;
            } else {
                $this->message = __('The coupon code could not be found.');
            }
        }

        if ($coupon) {
            if ($coupon->quantity <= $coupon->redeems) {
                $this->message = __('The coupon code has expired.');
            }
        } else {
            $this->message = __('Invalid coupon code.');
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
