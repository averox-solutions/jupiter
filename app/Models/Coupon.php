<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    public function scopeSearchCoupon(Builder $query, $value)
    {
        return $query->orWhere('name', 'like', '%' . $value . '%')
            ->orWhere('code', 'like', '%' . $value . '%');
    }

    public function scopeOfType(Builder $query, $value)
    {
        return $query->where('type', '=', $value);
    }
}
