<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecomendationData extends Model {

    protected $table = 'tbl_recomendation_data';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    // $data is Array Data
    // $additionalAttribute is Array Data
    public static function mapData($data, $additionalAttribute = null) {
        $result = [
            'id' => $data->id,
            'user_id' => $data->user_id,
            'data_id' => $data->data_id,
            'recomendation_type' => $data->recomendation_type,
            'price' => $data->price,
            'date' => $data->date,
        ];
        if($additionalAttribute) {
            $result = array_merge($result, $additionalAttribute);
        }
        return $result;
    }

}
