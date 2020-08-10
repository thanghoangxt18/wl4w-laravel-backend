<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function respondWithToken($token)
    {
        $email = Auth::user()->email;
        return response()->json([
            'email' => $email,
            'token' => $token,
            'token_type' => 'bearer',
            'expries_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
