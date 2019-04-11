<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FireStation extends Model
{
    protected $fillable = [
        'name', 'latitude', 'longitude', 'username', 'password'
    ];

    protected $hidden = [
        'password'
    ];
}
