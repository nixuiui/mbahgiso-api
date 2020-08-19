<?php

namespace App\Http\Controllers;

use App\Traits\BaseResponseTrait;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use BaseResponseTrait;

    public function uploadImage($file) {
        $data = array("file" => $file);
        $data_string = json_encode($data);

        $ch = curl_init(env('APP_STORAGE_URL') . 'upload-image.php');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = json_decode(curl_exec($ch));
        if($result->success == true) {
            return $result->filename;
        }
        else {
            return null;
        }
    }
}
