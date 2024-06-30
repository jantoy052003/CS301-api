<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;

class AddressController extends Controller
{

     /**
     * Create Address
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'street' => 'string',
            'city' => 'string',
            'state' => 'string',
            'country' => 'string',
            'zip' => 'string',
            'phone' => 'string',
        ]);

        $address = Address::create([
            'street' => $fields['street'],
            'city' => $fields['city'],
            'state' => $fields['state'],
            'country' => $fields['country'],
            'zip' => $fields['zip'],
            'phone' => $fields['phone'],
        ]);

        $response = [
            'address' => $address
        ];

        return response($response, 201);
    }

    /**
     * Display Address
     */
    public function getAddress(Request $request, string $id)
    {
        $address = Address::where('id', $id)
            ->orderBy('created_at', 'desc')
            ->get(['street', 'city', 'state', 'country', 'zip', 'phone']);

        return response ([
            'address' => $address
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateAddress(Request $request, string $id)
    {
        $address = Address::where('id',$id);
            //->where('id', auth()->user()->$id);

        $fields = $request->only([ 'street', 'city', 'state', 'country', 'zip', 'phone']);

        if (empty(array_filter($fields))) {
            return response ([
                'message' => 'Cannot update empty fields'
            ], 400);
        }

        $address->update([
            'street' => $fields['street'] ? $fields['street'] : $address->street,
            'city' => $fields['city'] ? $fields['city'] : $address->city,
            'state' => $fields['state'] ? $fields['state'] : $address->state,  
            'country' => $fields['country'] ? $fields['country'] : $address->country,  
            'zip' => $fields['zip'] ? $fields['zip'] : $address->zip,  
            'phone' => $fields['phone'] ? $fields['phone'] : $address->phone,  
        ]);

        return response ([
            'message' => 'Address has been updated successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
