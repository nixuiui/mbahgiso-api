<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dividen;
use App\Models\DividenForUser;
use App\Models\Market;
use App\Models\Recomendation;
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
        $price = RecomendationPrice::where("recomendation", $request->recomendation)->first();
        if(!$price) return $this->responseError("Harga Tidak Ada");

        if($request->type == "recomendation") {
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
            $data->price = $request->price;
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

    public function recomendationTrading() {
        return Recomendation::where("kategori", "trading")->get();
    }
    
    public function recomendationSwing() {
        return Recomendation::where("kategori", "swing")->get();
    }

    public function recomendationInvest() {
        return Recomendation::where("kategori", "invest")->get();
    }
    
    public function marketIndex() {
        return Market::where("market_category", "index")->get();
    }
    
    public function marketKomoditas() {
        return Market::where("market_category", "komoditas")->get();
    }
    
    public function dividens() {
        return Dividen::get();
    }

    public function checkRecomendationData(Request $request) {
        $data = RecomendationData::where("data_id", $request->data_id)
                ->where("recomendation_type", $request->recomendation)
                ->where("date", $request->date)
                ->first();
        
        if(!$data) return $this->responseError("Belum beli");
        return $this->responseOK("OK");

        return 0;
    }

    public function buyDividen(Request $request) {
        $check = DividenForUser::where("data_id", $request->data_id)
                    ->where("user_id", Auth::id())
                    ->first();
        if($check) return $this->responseError("Sudah beli");

        $data = new DividenForUser;
        $data->user_id = Auth::id();
        $data->data_id = $request->data_id;
        $data->price = $request->price;
        $data->save();
        
        return $this->responseOK("OK");
    }

    public function checkDividen(Request $request) {
        $check = DividenForUser::where("data_id", $request->data_id)
                    ->where("user_id", Auth::id())
                    ->first();
        if(!$check) return $this->responseError("Belum beli");
        return $this->responseOK("OK");
    }

}