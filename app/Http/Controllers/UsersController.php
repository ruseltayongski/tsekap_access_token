<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Muncity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function createUser() {
        $user = new User();
        $user->name = 'Anti Mage';
        $user->email = 'antimage';
        $user->password = Hash::make('123');
        $user->save();

        return 'successfully create user';
    }


    public function expiredAccessToken() {
        return response([
            'error' => 'Unauthorized',
            'message' => 'Your access token was expired',
            'status' => '401'
        ]);
    }

    public function retrieveUsers() {
        return User::orderBy("id","desc")->limit(10)->get();
    }

    public function getUserProfile(Request $request) {
        return Auth::user();
        //return $request->bearerToken();
    }

    public function getBarangay(Request $request) {
        return $request->barangay_id ? Barangay::find($request->barangay_id) : Barangay::where("muncity_id",Auth::user()->muncity)->get();
    }

    public function getMunicipality() {
        return Muncity::find(Auth::user()->muncity);
    }
}
