<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'data' => $user,
        ], 201);
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                "error" => "Wrong password"
            ], 500);
        }
        return response()->json([
            "access_token" => $token,
            "data" => $user,
        ], 201);
    }
}
