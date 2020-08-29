<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecomendationData;
use App\Models\RecomendationForUser;
use App\Models\RecomendationPrice;
use Illuminate\Http\Request;
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

    public function buyRecomendation(Request $request) {
        if($request->type == "recomendation") {
            $price = RecomendationPrice::where("recomendation", $request->recomendation)->first();
            if(!$price) return $this->responseError("Harga Tidak Ada");
            
            $check = RecomendationForUser::where("date", date("Y-m-d"))
                        ->where("recomendation_type", $request->recomendation)
                        ->where("user_id", Auth::id())
                        ->first();
            if($check) return $this->responseError("Sudah beli hari ini");
    
            $data = new RecomendationForUser;
            $data->user_id = Auth::id();
            $data->recomendation_type = $request->recomendation;
            $data->price = $price->price;
            $data->date = date("Y-m-d");
            $data->save();
            
            $data = RecomendationForUser::where("date", date("Y-m-d"))->get();
            $data = $data->map(function($item){
                return RecomendationForUser::mapData($item);
            });
            return $this->responseOK($data);
        } else if($request->type == "recomendation-data") {
            $price = RecomendationPrice::where("recomendation", $request->recomendation)->first();
            if(!$price) return $this->responseError("Harga Tidak Ada");
            
            $check = RecomendationData::where("date", date("Y-m-d"))
                        ->where("data_id", $request->data_id)
                        ->where("recomendation_type", $request->recomendation)
                        ->where("user_id", Auth::id())
                        ->first();
            if($check) return $this->responseError("Sudah beli hari ini");
    
            $data = new RecomendationData;
            $data->user_id = Auth::id();
            $data->data_id = $request->data_id;
            $data->recomendation_type = $request->recomendation;
            $data->price = $price->price;
            $data->date = date("Y-m-d");
            $data->save();
            
            $data = RecomendationData::where("date", date("Y-m-d"))->get();
            $data = $data->map(function($item){
                return RecomendationData::mapData($item);
            });
            return $this->responseOK($data);
        }
    }
    
    public function buyRecomendationData(Request $request) {
        return $request;
        $price = RecomendationPrice::where("recomendation", $request->recomendation)->first();
        if(!$price) return $this->responseError("Harga Tidak Ada");
        
        $check = RecomendationData::where("date", date("Y-m-d"))
                    ->where("recomendation_type", $request->recomendation)
                    ->where("user_id", Auth::id())
                    ->first();
        if($check) return $this->responseError("Sudah beli hari ini");

        $data = new RecomendationData;
        $data->user_id = Auth::id();
        $data->recomendation_type = $request->recomendation;
        $data->data_id = $request->data_id;
        $data->price = $price->price;
        $data->date = date("Y-m-d");
        $data->save();
        
        $data = RecomendationData::where("date", date("Y-m-d"))->get();
        $data = $data->map(function($item){
            return RecomendationData::mapData($item);
        });
        return $this->responseOK($data);
    }
    
    public function todayRecomendation() {
        $data = RecomendationForUser::where("date", date("Y-m-d"))->get();
        $data = $data->map(function($item){
            return RecomendationForUser::mapData($item);
        });
        return $this->responseOK($data);
    }
    
    public function todayRecomendationData() {
        $data = RecomendationData::where("date", date("Y-m-d"))->get();
        $data = $data->map(function($item){
            return RecomendationData::mapData($item);
        });
        return $this->responseOK($data);
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
