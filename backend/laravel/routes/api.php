<?php

use app\Http\Controllers\UserController;
use app\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;


Route::get('/users',[UserController::class,'index']);
Route::get('/device',[DeviceController::class,'index']);

?>
