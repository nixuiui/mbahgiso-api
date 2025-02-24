<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceTopup extends Model {

    protected $table = 'tbl_balance_topups';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    // $data is Array Data
    // $additionalAttribute is Array Data
    public static function mapData($data, $additionalAttribute = null) {
        $result = [
            'id' => $data->id,
            'name' => $data->user->name,
            'phone_number' => $data->user->phone_number,
            'balance' => $data->balance,
            'status' => $data->status,
            'expired_date' => $data->expired_date,
            'transfer_to' => "085269963564",
            'whatsapp' => "6285269963564",
            'approved_date' => $data->approved_date,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
        if($additionalAttribute) {
            $result = array_merge($result, $additionalAttribute);
        }
        return $result;
    }

}
