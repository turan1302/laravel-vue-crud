<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(['prefix'=>'product','namespace'=>'product'],function (){
    Route::get('',[\App\Http\Controllers\api\product\indexController::class,'index']);
    Route::post('save',[\App\Http\Controllers\api\product\indexController::class,'save_product']);
    Route::group(['prefix'=>'{item}'],function (){
        Route::get('delete',[\App\Http\Controllers\api\product\indexController::class,'delete']);
        Route::post('active',[\App\Http\Controllers\api\product\indexController::class,'isActiveSetter']);
        Route::get('edit',[\App\Http\Controllers\api\product\indexController::class,'edit']);
        Route::post('update',[\App\Http\Controllers\api\product\indexController::class,'update']);
    });
});
