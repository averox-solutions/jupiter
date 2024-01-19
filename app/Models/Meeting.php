<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    //user meeting relation
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
