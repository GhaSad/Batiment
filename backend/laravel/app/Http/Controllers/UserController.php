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
            'date_de_naissance' => 'required|date',
            'sexe' => 'required|in:homme,femme,autre',  // Validation du sexe
            'role' => 'required|in:visiteur,simple,complexe,admin',
            'home_id' => 'required|integer|max:255',
        ]);

        $dateNaissance = \Carbon\Carbon::parse($request->date_de_naissance);
        $age = $dateNaissance->age; // Utilisation de Carbon pour calculer l'âge

        // Création de l'utilisateur
        try {
            $user = User::create([
                'username' => $request->prenom . ' ' . $request->nom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'visiteur',
                'date_de_naissance' => $request->date_de_naissance,  // Enregistrement de la date de naissance
                'sexe' => $request->sexe,  // Enregistrement du sexe
                'age' => $age,
                'home_id' => $request->home_id,
            ]);

            Log::info('Utilisateur créé : ', ['user' => $user]);

        } catch (\Exception $e) {
            // Si une erreur se produit pendant la création de l'utilisateur, afficher l'exception
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

        // Vérifie si l'utilisateur a été créé avec succès
        if ($user) {
            return redirect()->route('login')->with('success', 'Inscription réussie !');
        } else {
            return redirect()->route('register.form')->with('error', 'Une erreur est survenue.');
        }
    }

    public function createUser(Request $request)
{
    $validated = $request->validate([
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'date_de_naissance' => 'required|date',
        'sexe' => 'required|in:homme,femme,autre',
        'role' => 'required|in:visiteur,simple,admin,complexe', // Profils possibles
        'home_id' => 'required|integer|max:255',
    ]);

    $dateNaissance = \Carbon\Carbon::parse($request->date_de_naissance);
    $age = $dateNaissance->age; // Calculer l'âge avec Carbon

    // Créer l'utilisateur
    $user = User::create([
        'username' => $request->prenom . ' ' . $request->nom,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // Vous pouvez ajouter une logique pour les rôles
        'date_de_naissance' => $request->date_de_naissance,
        'sexe' => $request->sexe,
        'age' => $age,
        'home_id' => $request->home_id,
    ]);
    Log::info('Redirection vers la page d\'accueil après la création de l\'utilisateur');

    return redirect()->route('home')->with('success', 'Utilisateur créé avec succès');
}

}
