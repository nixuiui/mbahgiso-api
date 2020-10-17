<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recomendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecomendationController extends Controller
{
    public function getData() {
        $type = $_GET['type'] ?? "";
        $date = $_GET['date'] ?? date("Y-m-d");
        $data = Recomendation::where("kategori", "LIKE", "%".$type."%")
                    ->where("date", $date)
                    ->get();
        return $data;
    }
    
    public function addData(Request $input) {
        $data = new Recomendation;
        $data->kategori = $input->kategori;
        $data->kode_saham = $input->kode_saham;
        $data->potensi_kenaikan = $input->potensi_kenaikan;
        $data->prospek_perusahaan = $input->prospek_perusahaan;
        $data->fundamental = $input->fundamental;
        $data->teknikal = $input->teknikal;
        $data->jual_beli = $input->jual_beli;
        $data->harga_beli = $input->harga_beli;
        $data->date = $input->date;
        $data->save();
        return $data;
    }
    
    public function editData(Request $input, $id) {
        $data = Recomendation::find($id);
        if(!$data) return $this->responseError("Data tidak ada");

        $data->kategori = $input->kategori;
        $data->kode_saham = $input->kode_saham;
        $data->potensi_kenaikan = $input->potensi_kenaikan;
        $data->prospek_perusahaan = $input->prospek_perusahaan;
        $data->fundamental = $input->fundamental;
        $data->teknikal = $input->teknikal;
        $data->jual_beli = $input->jual_beli;
        $data->harga_beli = $input->harga_beli;
        $data->date = $input->date;
        $data->save();
        return $data;
    }
    
    public function deleteData(Request $input, $id) {
        $data = Recomendation::find($id);
        if(!$data) return $this->responseError("Data tidak ada");
        if($data->delete())
        return $this->responseOK("Berhasil Menghapus");
    }

}