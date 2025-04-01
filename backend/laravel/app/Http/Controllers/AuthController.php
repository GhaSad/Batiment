<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Si tu utilises Auth pour l'authentification

class AuthController extends Controller
{
    // Méthode pour afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('accueil');
    }

    // Méthode pour traiter la soumission du formulaire de connexion
public function login(Request $request)
{
    // Validation des entrées
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    // Authentification
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect()->route('home');
    }else {
        return back()->withErrors(['email' => 'Les identifiants sont incorrects.']);
    }
}

public function index()
{
    $user = Auth::user();  // Récupère l'utilisateur connecté

    // Passer le rôle de l'utilisateur à la vue
    return view('home', ['role' => $user->role]);
}

}
