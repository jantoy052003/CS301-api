<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Menu_listController;

// Route::get('/menu_list', [Menu_listController::class, 'index']);
// Route::post('/menu_list', [Menu_listController::class, 'store']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//Public 
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::get('/menu_list', [Menu_listController::class, 'index']);
// Route::get('/menu_list/{menuId}', [Menu_listController::class, 'getMenuImage'])->where('menuId', '[0-9]+');
// Route::post('/create', [Menu_listController::class, 'create']);
// Route::post('/change/{menuId}', [Menu_listController::class, 'change']);

//Protected
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/image/{userId}', [ImageController::class, 'getImage'])->where('userId', '[0-9]+');
    Route::post('/upload/{userId}', [ImageController::class, 'upload']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request){
    return $request->user();
}); 
