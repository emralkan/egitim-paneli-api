<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }



    public function userLogin(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            return response()->json(['user' => $user]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
