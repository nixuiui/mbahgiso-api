<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function getData() {
        $offset = 0;
        $limit = 10;
        $search = "s";

        if(isset($_GET['limit']) && $_GET['limit'] > 0) 
            $limit = $_GET['limit'];
        if(isset($_GET['page']) && $_GET['page'] > 0) 
            $offset = ($_GET['page']-1)*$limit;
        if(isset($_GET['search']) && $_GET['search']) 
            $search = $_GET['search'];
        $data = User::offset($offset)
                        ->limit($limit)
                        ->where("name", "like", "%" . $search . "%")
                        ->orderBy("name", "asc")
                        ->get();
        $data = $data->map(function($item) {
            return User::mapDataAdmin($item);
        });
        return $data;
    }

}