<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    
    public function login(Request $request) {
        $loginWithPhoneNumber = app('auth')->attempt(['phone_number' => $request->username, 'password' => $request->password]);
        $loginWithUsername = app('auth')->attempt(['username' => $request->username, 'password' => $request->password]);
        if ($loginWithPhoneNumber) {
            $token = $loginWithPhoneNumber;
        }
        else if($loginWithUsername) {
            $token = $loginWithUsername;
        }
        else {
            return $this->responseError("Maaf user tidak ditemukan.");
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'username'              => 'required',
            'phone_number'          => 'required',
            'password'              => 'required|min:6',
        ]);
        if ($validator->fails()) return $this->responseInvalidInput($validator->errors());

        $check = User::where("username", $request->username)->first();
        if($check) return $this->responseError("Maaf username sudah digunakan");

        $check = User::where("phone_number", $request->phone_number)->first();
        if($check) return $this->responseError("Maaf No. HP sudah digunakan");

        if($request->phone_number[0] == "0") $request->phone_number = substr($request->phone_number, 1);
        if(substr($request->phone_number, 0, 3) == "620") {
            $str1 = substr($request->phone_number, 0, 2);
            $str2 = substr($request->phone_number, 3);
            $request->phone_number = $str1 . $str2;
        };
        if($request->phone_number[0] == "8") $request->phone_number = "62" . $request->phone_number;

        $user = new User;
        $user->role_id = 2;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = app('hash')->make($request->password);
        $user->city = $request->city;
        $user->phone_number = $request->phone_number;
        if($user->save()) {
            $loginWithUsername = app('auth')->attempt(['username' => $request->username, 'password' => $request->password]);
            if($loginWithUsername) {
                $token = $loginWithUsername;
            }
            else {
                return $this->responseError("Maaf user tidak ditemukan.");
            }
            return $this->respondWithToken($token);
        }
        return $this->responseError("Gagal Register");
    }

    public function forgotPassword(Request $request) {
        $user = User::where("email", $request->email)->first();
        if($user) {
            $user->password = app('hash')->make(123456);
            return $this->responseOK("Sementara, password diganti jadi 123456");
        }
        return $this->responseError("Email belum terdaftar");
    }

    public function username(Request $request) {
        if(User::where('username', $request->username)->first())
            return $this->responseError("Username sudah digunakan");
        return $this->responseOK("Username tersedia");
    }
    
    public function phoneNumber(Request $request) {
        if(User::where('phone_number', $request->phone_number)->first())
            return $this->responseError("Nomo HP sudah digunakan");
        return $this->responseOK("Nomo HP tersedia");
    }
    
    public function email(Request $request) {
        if(User::where('email', $request->email)->first())
            return $this->responseError("Email sudah digunakan");
        return $this->responseOK("Email tersedia");
    }

    // public function refresh() {
    //     return $this->respondWithToken(app('auth')->refresh());
    // }

    protected function respondWithToken($token) {
        $token = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => app('auth')->factory()->getTTL() * 10000
        ];
        $data = User::mapData(app('auth')->user(), $token);
        return $this->responseOK($data);
    }

    public function test() {
        return 0;
    }

    public function logout() {
        app('auth')->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->responseOK("OK");
    }
}