<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;

use App\Http\Controllers\AddressController;
use App\Models\Address;

class AuthController extends Controller
{
    //Register
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed',

            'street' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip' => 'nullable',
            'phone' => 'nullable',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $address = Address::create(array_filter($fields),[
            'street' => $fields['street'],
            'city' => $fields['city'],
            'state' => $fields['state'],
            'country' => $fields['country'],
            'zip' => $fields['zip'],
            'phone' => $fields['phone'],
        ]);

        $token = $user->createToken('projectapi')->plainTextToken;

        $response = [
            'user' => $user,
            'address' => $address,
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
    public function getProfile($id = null) {
        
        $profile = User::where('id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get(['name', 'last_name', 'email']);

        $address = Address::where('id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get(['street', 'city', 'state', 'country', 'zip', 'phone']);

        
            
        return response ([
            'profile' => $profile,
            'address' => $address
        ], 200);
    }

    //Update Profile
    public function update(Request $request, string $id) {

        $profile = User::where('id', auth()->user()->id)
            ->where('id', auth()->user()->id);

        $address = Address::where('id',$id);

        $fields = $request->only([ 'name', 'last_name', 'email', 'street', 'city', 'state', 'country', 'zip', 'phone']);

        if (empty(array_filter($fields))) {
            return response ([
                'message' => 'Cannot update empty fields'
            ], 400);
        }

        $profile->update([
            'name' => $fields['name'] ? $fields['name'] : $profile->name,
            'last_name' => $fields['last_name'] ? $fields['last_name'] : $profile->last_name,
            'email' => $fields['email'] ? $fields['email'] : $profile->email
        ]);

        $address->update([
            'street' => $fields['street'] ? $fields['street'] : $address->street,
            'city' => $fields['city'] ? $fields['city'] : $address->city,
            'state' => $fields['state'] ? $fields['state'] : $address->state,  
            'country' => $fields['country'] ? $fields['country'] : $address->country,  
            'zip' => $fields['zip'] ? $fields['zip'] : $address->zip,  
            'phone' => $fields['phone'] ? $fields['phone'] : $address->phone,  
        ]);

        return response ([
            'message' => 'Profile has been updated successfully'
        ], 200);
    }
}
