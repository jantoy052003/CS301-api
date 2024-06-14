<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Menu_list;
use App\Http\Requests\MenuListStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Menu_listController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Menu_list::all();
    }

    public function create (Request $request)
    {
        $request->validate ([
            'menu_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        $image_name = 'menu'. '.' . $request->menu_image->extension();
        $request->menu_image->move(storage_path('app/public/menu_images'), $image_name);

        Menu_list::create([
            'menu_name' => $request->menu_name,
            'menu_image' => $image_name
        ]);

        $response = [
            'message' => 'Upload successful',
        ];

        return response($response, 200);
    }

    public function change (Request $request, $menuId)
    {   

        $request->validate ([
            'menu_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        $menu = Menu_list::findOrFail($menuId);
        $image_name = 'menu_' . $menu->id . '.' . $request->menu_image->extension();
        //$image_name = 'menu'. '.' . $request->menu_image->extension();
        $request->menu_image->move(storage_path('app/public/menu_images'), $image_name);

        $menu->menu_image = $image_name;
        $menu->save();

        // Menu_list::create([
        //     'menu_name' => $request->menu_name,
        //     'menu_image' => $image_name
        // ]);

        $response = [
            'message' => 'Upload successful',
        ];

        return response($response, 200);

        // try {
        //     $imageName = Str::random(12) . "." . $request->menu_image->getClientOriginalExtension();
        //     //$imageName = 'menu_image' . "." . $request->menu_image->getClientOriginalExtension();
            
        //     // Create Menu List
        //     Menu_list::create([
        //         'menu_name' => $request->menu_name,
        //         'menu_image' => $imageName
        //     ]);

        //     // Save Image in Storage folder
        //     Storage::url('public')->put($imageName, file_get_contents($request->menu_image));
            
        //     // Return Json Response
        //     return response()->json([
        //         'message' => 'Item successfully added.'
        //     ], 200);

        // } catch (\Exception $e) {
        //     // Return Json Response
        //     return response()->json([
        //         'message' => 'Something went wrong!'
        //     ], 500);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function getMenuImage(Request $request, $menuId)
    {
        $menu = Menu_list::find($menuId);
        if (!$menu) {
             abort(Response::HTTP_NOT_FOUND, 'Menu not found');
        }

        if ($menu->menu_image) {
            $imagePath = storage_path('app/public/menu_images/' . $menu->menu_image);
            if (file_exists($imagePath)) {
                $image = file_get_contents($imagePath);
                return response($image, 200)->header('Content-Type', 'image/jpeg');
            } 
        }

        // $defaultImagePath = storage_path('app/public/profile_images/default.png');
        // $defaultImage = file_get_contents($defaultImagePath);

        return response( 200)->header('Content-Type', 'image/jpeg');
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
