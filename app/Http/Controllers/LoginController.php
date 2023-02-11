<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBrgy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function invalidAccessToken() {
        return response([
            'error' => 'Unauthorized',
            'message' => 'Full authentication is required to access this resource',
            'status' => '401'
        ]);
    }

    public function login(Request $request) {
        $login = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if(!Auth::attempt($login)) {
            return response([
                'message' => 'Invalid Login Credentials'
            ]);
        }

        /*if(Auth::user()->user_priv != 5) {
            return response([
                'message' => 'Your account is not authorized'
            ]);
        }*/

        if (UserBrgy::where('user_id', Auth::user()->id)->isEmpty()) {
            return response([
                'message' => 'Your account is not authorized, no area assignment'
            ]);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response([
            'userid' => Auth::user()->id,
            'access_token' => $accessToken
        ]);

    }
}
