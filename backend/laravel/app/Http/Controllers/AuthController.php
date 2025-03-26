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
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('home');
        }
        
        return back()->withErrors(['email' => 'Les identifiants sont incorrects']);
    }
}
