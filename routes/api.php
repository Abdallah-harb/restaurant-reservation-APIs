<?php

use App\Http\Controllers\Api\Public\Auth\AuthController;
use App\Http\Controllers\Api\Public\DataController;
use App\Http\Controllers\Reservation\OrderController;
use App\Http\Controllers\Reservation\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => "public"],function(){
            //registration
    Route::post("register",[AuthController::class, "register" ]);
    Route::post("login",[AuthController::class, "login" ]);

            //auth
    Route::group(['middleware' => 'Auth_check'],function(){
        Route::get('meals',[DataController::class,'allMeals']);
        Route::get('tables',[DataController::class,'allTables']);
    });
});
        //reservation
Route::group(['prefix' => "reserve" , "middleware" => "Auth_check"],function(){
        Route::get('/',[ReservationController::class,'index']);
        Route::post('store',[ReservationController::class,'store']);
        Route::get('show/{id}',[ReservationController::class,'show']);
        Route::post('update/{id}',[ReservationController::class,'update']);
        Route::post('delete/{id}',[ReservationController::class,'destroy']);
                //orders
        Route::group(['prefix' => "order"],function(){
            Route::get('/',[OrderController::class,'index']);
            Route::post('store',[OrderController::class,'store']);
            Route::get('show/{id}',[OrderController::class,'show']);
            Route::post('update/{id}',[OrderController::class,'update']);
            Route::post('delete/{id}',[OrderController::class,'destroy']);
        });
});
