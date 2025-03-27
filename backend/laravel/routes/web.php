<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AuthController;  // N'oublie pas d'importer ce contrôleur
use App\Models\Device;
use App\Models\Logs;
use App\Models\Room;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route pour afficher la liste des utilisateurs
Route::get('/users', [UserController::class, 'index']);

// Route pour afficher la liste des dispositifs
Route::get('/device', [DeviceController::class, 'index']);

// Route pour afficher le formulaire d'inscription
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register.form');

// Route pour traiter la soumission du formulaire d'inscription
Route::post('/register', [UserController::class, 'register'])->name('register.submit');

// Affichage de la page d'accueil (formulaire de connexion)
Route::get('/accueil',[AuthController::class, 'showLoginForm'])->name('login.form');

// Traitement du formulaire de connexion
Route::post('/accueil', [AuthController::class, 'login'])->name('login');

// Route protégée pour la page d'accueil après connexion
Route::get('/home', [DeviceController::class, 'index'])->middleware('auth')->name('home');


// Route pour traiter la soumission du formulaire de création d'utilisateur
Route::post('/create-user', [UserController::class, 'createUser'])->name('create.user');

Route::post('/create-device', [DeviceController::class, 'store'])->name('create-device');

// Définir une route API pour récupérer les pièces
Route::get('/api/rooms', function () {
    $rooms = App\Models\Room::all();  // Récupérer toutes les pièces dans la base de données
    return response()->json($rooms);  // Retourner les données au format JSON
});


// Route API pour enregistrer des logs de sécurité (par exemple pour des actions de sécurité comme les portes, fenêtres, etc.)
Route::post('/api/security/log', function (Request $request) {
    // Validation des données
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'device_id' => 'required|exists:devices,id',
        'log_message' => 'required|string',
        'status' => 'required|in:actif,inactif'
    ]);

    // Créer et enregistrer un log
    $log = Log::create([
        'user_id' => $request->user_id,
        'device_id' => $request->device_id,
        'log_message' => $request->log_message,
        'status' => $request->status,
    ]);

    // Mettre à jour le statut du dispositif
    $device = Device::find($request->device_id);
    $device->changeStatus($request->status);

    return response()->json(['success' => true, 'log' => $log]);
});

// Utiliser une ressource pour gérer les dispositifs
Route::resource('devices', DeviceController::class);

Route::post('/add-object', [DeviceController::class, 'store'])->name('device-add');

?>
