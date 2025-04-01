<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AuthController;  // N'oublie pas d'importer ce contrôleur
use App\Http\Controllers\RoomController;
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

Route::get('/api/utilisateurs', [UserController::class, 'index']);

// Route pour traiter la soumission du formulaire d'inscription
Route::post('/register', [UserController::class, 'register'])->name('register.submit');



// Affichage de la page d'accueil (formulaire de connexion)
Route::get('/accueil',[AuthController::class, 'showLoginForm'])->name('login.form');

// Traitement du formulaire de connexion
Route::post('/accueil', [AuthController::class, 'login'])->name('login');



// Route protégée pour la page d'accueil après connexion
Route::get('/home', [DeviceController::class, 'index'])->middleware('auth')->name('home');

// Route pour traiter la soumission du formulaire de création d'utilisateur
Route::post('/create-user', [UserController::class, 'store'])->name('create.user');


Route::post('/create-device', [DeviceController::class, 'store'])->name('create-device');

// Définir une route API pour récupérer les pièces
Route::get('/api/rooms', function () {
    $rooms = App\Models\Room::all();  // Récupérer toutes les pièces dans la base de données
    return response()->json($rooms);  // Retourner les données au format JSON
});

//Formulaire pour ajout objets connecté

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

Route::get('/objets',[DeviceController::class,'index']);

Route::post('/add-object', [DeviceController::class, 'store'])->name('device-add');



// Route pour afficher toutes les pièces (accessible depuis l'interface)
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

// Route pour créer une pièce
Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');

// Dans le contrôleur
Route::get('/api/rooms', function () {
    $rooms = App\Models\Room::where('home_id', auth()->user()->home_id)->get();  // Récupérer toutes les pièces de la maison de l'utilisateur
    return response()->json($rooms);  // Retourner les données au format JSON
});


Route::post('/add-connected-object', [DeviceController::class, 'store'])->name('add-connected-object');

Route::get('/api/devices/{type}', [DeviceController::class, 'getDevicesByType']);

Route::get('/api/devices', [DeviceController::class, 'getDevices']);

Route::patch('/api/devices/{id}/status', [DeviceController::class, 'updateDeviceStatus']);

Route::post('/logout', function () {
    Auth::logout();  // Déconnexion de l'utilisateur
    return response()->json(['success' => true]);  // Retourne un message JSON de succès
})->name('logout');

?>
