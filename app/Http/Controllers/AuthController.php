<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        //validate
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        //end validation
        $input = $request->only('name', 'email', 'password');
        try {
            $user = new User;
            $user->name = $input['name'];
            $user->email = $input['email'];
            $password = $input['password'];
            $user->password = app('hash')->make($password);

            if ($user->save()) {
                //return successful response
                return $this->successResponse(['user' => $user, 'message' => 'CREATED'], 'success');
            }
        } catch (\Exception $e) {
            //return error message
            return $this->errorResponse($e->getMessage(), 409);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            $data = $this->respondWithToken($token)->original;
            return $this->successResponse($data,'success');
        }
        return $this->errorResponse('Unauthorized', 401);
    }

    public function detail(){
        $user = Auth::user();
        return $this->successResponse($user,'Success',200);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
