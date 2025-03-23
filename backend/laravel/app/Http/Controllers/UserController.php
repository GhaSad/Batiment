<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Afficher le formulaire d'inscription
    public function showRegistrationForm()
    {
        return view('register');  // Vue de formulaire d'inscription
    }

    // Gérer l'inscription
    public function register(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    // Création de l'utilisateur
    try {
        $user = User::create([
            'username' => $request->prenom . ' ' . $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'visiteur',
        ]);

        Log::info('Utilisateur créé : ', ['user' => $user]);

    } catch (\Exception $e) {
        // Si une erreur se produit pendant la création de l'utilisateur, afficher l'exception
        return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
    }

    // Vérifie si l'utilisateur a été créé avec succès
    if ($user) {
        return redirect()->route('register.form')->with('success', 'Inscription réussie !');
    } else {
        return redirect()->route('register.form')->with('error', 'Une erreur est survenue.');
    }
}

    
}
