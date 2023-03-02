<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Muncity;
use App\Models\User;
use App\Models\UserBrgy;
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

    public function getUserInfo(Request $request) {
        return User::find($request->id);
    }

    public function getUserBarangay(Request $request) {
        return UserBrgy::
            select("barangay.*")
            ->where("userbrgy.user_id",$request->userid)
            ->leftJoin("barangay","barangay.id","=","userbrgy.barangay_id")
            ->get();
    }

    public function textBlast(Request $request) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                 "to" : "'.$request->to.'",
                 "notification" : {
                    "body" : '.$request->message.',
                    "title" : "Text Blast"
                 }
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: key=AAAAd6LG4Vo:APA91bGq5QLdpH_gX0RyrYnUNshipWO9K6y2o-VKmWbJTM6B4VSioJa4qHd5YjO8P244Buc8qXreuSP2aDBhf0DKEq_T_SmcCbPwOxAONnsnrsDgnitLUWIZ_PDQ3ANWshBTHzCxCGLn',
                'Content-Type: application/json'
            ),
        ));

        curl_exec($curl);
        curl_close($curl);
    }

}
