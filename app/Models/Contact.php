<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'user_id'
    ];

    //user contact relation
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
