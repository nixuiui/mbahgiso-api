<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DividenForUser extends Model {

    protected $table = 'tbl_dividen_for_user';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

}
