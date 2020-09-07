<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BalanceTopup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{

    public function getProfileDetail() {
        $class = User::where("id", Auth::user()->id)->first();
        if (!$class) {
            return $this->responseError("Data Tidak Ditemukan");
        }
        return $this->responseOK(User::mapData($class));
    }

    public function editProfile(Request $request) {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'email' => "email|unique:tbl_users,email,$id",
            'username' => "unique:tbl_users,username,$id",
            'whatsapp' => "unique:tbl_users,whatsapp,$id",
        ]);
        if ($validator->fails()) {
            return $this->responseInvalidInput($validator->errors());
        }

        $data = User::find(Auth::user()->id);
        if (!$data) {
            return $this->responseError("User Tidak Ditemukan");
        }

        if ($request->name) {
            $data->name = $request->name;
        }
        if ($request->email) {
            $data->email = $request->email;
        }
        if ($request->username) {
            $data->username = $request->username;
        }

        if ($request->old_password) {
            if ($request->password) {
                if (Hash::check($request->old_password, $data->password)) {
                    if ($request->password) {
                        if ($request->re_password) {
                            if ($request->password == $request->re_password) {
                                $hashed = Hash::make($request->password);
                                $data->password = $hashed;
                            } else {
                                return $this->responseError("Password baru anda tidak sama !");
                            }
                        } else if (!$request->re_password) {
                            return $this->responseError("Masukan ulang password baru anda !");
                        }
                    } else if (!$request->password) {
                        return $this->responseError("Masukan password baru anda !");
                    }
                } else if (!$request->password) {
                    return $this->responseError("Password anda tidak cocok !");
                }
            } else {
                return $this->responseError("Masukan Password Baru Anda !");
            }
        }

        $data->save();
        return $this->responseOK(User::mapData($data));
    }

    public function topup(Request $request) {
        $now = date("Y-m-d H:i:s");
        $topup = new BalanceTopup;
        $topup->user_id = Auth::user()->id;
        $topup->balance = $request->balance;
        $topup->status = "unpaid";
        $topup->expired_date = date("Y-m-d H:i:s", strtotime($now . ' +1 day'));
        $topup->save();
        return $this->responseOK(BalanceTopup::mapData($topup));
    }

}
