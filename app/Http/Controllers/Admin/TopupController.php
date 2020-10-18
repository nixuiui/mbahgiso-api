<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceTopup;
use App\Models\Recomendation;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function getData() {
        $offset = 0;
        $limit = 10;

        if(isset($_GET['limit']) && $_GET['limit'] > 0) 
            $limit = $_GET['limit'];
        if(isset($_GET['page']) && $_GET['page'] > 0) 
            $offset = ($_GET['page']-1)*$limit;

        $data = BalanceTopup::offset($offset)->limit($limit)->get();
        $data = $data->map(function($item) {
            return BalanceTopup::mapData($item);
        });
        return $data;
    }

    public function verifyTopup($id) {
        $data = BalanceTopup::find($id);
        if(!$data) return $this->responseError("Data tidak ditemukan");

        $data->status = "paid";
        $data->save();
        return BalanceTopup::mapData($data);
    }

}