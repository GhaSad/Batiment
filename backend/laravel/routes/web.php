<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;


Route::get('/users',[UserController::class,'index']);
Route::get('/device',[DeviceController::class,'index']);

// Route pour afficher le formulaire (optionnel si tu veux afficher une page dédiée)
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register.form');

// Route pour traiter la soumission du formulaire
Route::post('/register', [UserController::class, 'register'])->name('register.submit');

Route::get('/accueil', function () {
    return view('accueil');  // Vue de l'accueil
});

Route::get('/home',function(){
    return view('home');
});
?>
