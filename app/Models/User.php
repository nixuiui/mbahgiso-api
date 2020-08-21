<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, JWTSubject {

    use Authenticatable, CanResetPassword;

    protected $table        = 'tbl_users';
    protected $hidden       = [
        'password', 'remember_token',
    ];
    public $timestamps = false;


    protected static function boot() {
        parent::boot();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function recomendations() {
        return $this->hasMany('App\Models\RecomendationForUser', 'user_id');
    }

    // $data is Array Data
    // $additionalAttribute is Array Data
    public static function mapData($data, $additionalAttribute = null) {
        $result = [
            'id' => $data->id,
            'name' => $data->name,
            'username' => $data->username,
            'password' => $data->password,
            'phone_number' => $data->phone_number,
            'city' => $data->city,
            'balance' => $data->balance,
        ];
        if($additionalAttribute) {
            $result = array_merge($result, $additionalAttribute);
        }
        return $result;
    }

}