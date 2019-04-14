<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FireHydrant extends Model
{
    protected $fillable = [
        'name', 'latitude', 'longitude'
    ];
}
