<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    //Register
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'last_name' => $fields['last_name'],
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

    //Get Profile
    public function getProfile($user_id = null) {
        
        $profile = User::where('id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get(['name', 'last_name', 'email']);

            
        return response ([
            'profile' => $profile
        ], 200);
    }

    //update Profile
    public function update(Request $request, string $id) {

        $profile = User::where('id', auth()->user()->id)
            ->where('id', auth()->user()->id);

        $fields = $request->only(['name', 'last_name', 'email']);

        if (empty(array_filter($fields))) {
            return response ([
                'message' => 'Cannot update empty fields'
            ], 400);
        }

        $profile->update([
            'name' => $fields['name'] ? $fields['name'] : $profile->name,
            'last_name' => $fields['last_name'] ? $fields['last_name'] : $profile->last_name,
            'email' => $fields['email'] ? $fields['email'] : $profile->email,  
        ]);

        return response ([
            'message' => 'Profile has been updated successfully'
        ], 200);
    }
}
