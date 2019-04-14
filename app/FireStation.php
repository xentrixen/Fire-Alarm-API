<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

class FireStation extends Authenticatable
{
    use HasMultiAuthApiTokens;

    protected $fillable = [
        'name', 'latitude', 'longitude', 'username', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function findForPassport($username) {
        return $this->where('username', $username)->first();
    }
}
