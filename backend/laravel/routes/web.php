<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;


Route::get('/users',[UserController::class,'index']);
Route::get('/device',[DeviceController::class,'index']);

?>
