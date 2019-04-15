<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FireReport extends Model
{
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:F j, Y g:i A',
    ];

    protected $fillable = [
        'citizen_id', 'latitude', 'longitude', 'image', 'image_id', 'level_of_fire'
    ];

    protected $hidden = [
        'citizen_id', 'updated_at', 'image_id'
    ];

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }

    public function citizen() {
        return $this->belongsTo('App\Citizen');
    }
}
