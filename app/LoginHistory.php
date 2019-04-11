<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $casts = [
        'created_at' => 'datetime:F j, Y g:i A',
    ];

    protected $fillable = [
        'citizen_id'
    ];

    protected $hidden = [
        'citizen_id', 'updated_at'
    ];

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }

    public function citizen() {
        return $this->belongsTo('App\Citizen');
    }
}
