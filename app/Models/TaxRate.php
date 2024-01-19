<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends Model
{
    use SoftDeletes;

    protected $casts = [
        'regions' => 'object'
    ];

    public function scopeSearchName(Builder $query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function scopeOfType(Builder $query, $value)
    {
        return $query->where('type', '=', $value);
    }

    public function scopeOfRegion(Builder $query, $value)
    {
        $query->whereNull('regions')
            ->when($value, function ($query) use ($value) {
                $query->orWhere('regions', 'like', '%' . $value . '%');
            });

        return $query;
    }
}
