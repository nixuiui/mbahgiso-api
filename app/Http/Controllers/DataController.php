<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecomendationForUser;
use App\Models\SetGeneral;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function getRecomendation($type) {
        $data = RecomendationForUser::where("user_id", Auth::user()->id)
                        ->where("date", date("Y-m-d"))
                        ->first();
        if(!$data) return $this->responseError("Maaf Anda belum bisa mengakses rekomendasi " . $type . " hari ini");
        return $this->responseOK(RecomendationForUser::mapData($data));
    }

    public function marketIndex() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://script.google.com/macros/s/AKfycbxS4eCKGo--UbuEyXorXpxUZU9nh9l7zoDt5Dpyx1cqZ1zVRK4/exec?id=1nRpCP1qZ5LjNZApDS4w5Xg4MTxfGhgFC8ews0_tpipc&amp;sheet=index");
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
        $result = json_decode(curl_exec($ch));
        return $result;
        // if($result->success == true) {
        // }
        // else {
        //     return null;
        // }
    }
}
