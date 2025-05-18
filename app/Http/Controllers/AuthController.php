<?php

namespace App\Http\Controllers;


// use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'nidn' => 'required|string|max:15|unique:users,nidn',
                'role' => 'required|in:dekan,wakil dekan,staff,admin',
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validator->fails()) :
                $validatorMessage = $validator->errors()->first();
                return response()->json(['status' => 'failed', 'message' => $validatorMessage]);

            else:
                $user = User::create([
                    'name' => $request->name,
                    'nidn' => $request->nidn,
                    'role' => $request->role,
                    'password' => Hash::make($request->password),
                ]);

                $token = JWTAuth::fromUser($user);

                return response()->json(['status' => 'success', 'token' => $token, 'data' => $user], 201);

            endif;
        } catch (\Throwable $error) {
            return response()->json(['status' => 'failed', 'message' => 'Error ' . $error]);
        }
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('nidn', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }


    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
}
