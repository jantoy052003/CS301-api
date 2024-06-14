<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Register
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('projectapi')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    //Log Out
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return[
            'message' => "Logged out"
        ];
    }

    //Log In
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //Check Email
        $user = User::where('email', $fields['email'])->first();

        if (!$user) {
            return response([
                'message' => 'Incorrect email or password'
            ], 401);
        }

        //Check Password
        if(!Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Incorrect password'
            ], 401);
        }

        $token = $user->createToken('projectapi')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }
}
