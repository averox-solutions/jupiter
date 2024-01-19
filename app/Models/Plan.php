<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $casts = [
        'items' => 'object',
        'tax_rates' => 'object',
        'coupons' => 'object',
        'features' => 'object'
    ];

    /**
     * Get the plan price status
     */
    public function hasPrice()
    {
        return $this->amount_month || $this->amount_year;
    }

    public function scopeSearchName(Builder $query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function scopeOfVisibility(Builder $query, $value)
    {
        return $query->where('visibility', '=', $value);
    }

    public function scopePriced(Builder $query)
    {
        return $query->where([['amount_month', '>', 0], ['amount_year', '>', 0]]);
    }

    public function scopeDefault(Builder $query)
    {
        return $query->where([['amount_month', '=', 0], ['amount_year', '=', 0]]);
    }
}
