<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        // Récupérer tous les utilisateurs
        $users = User::all(); // Tu peux filtrer ou paginer si nécessaire

        return response()->json($users); // Renvoie la liste des utilisateurs sous forme de JSON
    }

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
                'role' => 'admin',
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
        'role' => 'required|in:invite,enfant,admin,parent',
    ]);

    $dateNaissance = \Carbon\Carbon::parse($request->date_de_naissance);
    $age = $dateNaissance->age; // Calculer l'âge avec Carbon

    $homeId = Auth::user()->home_id;
    // Créer l'utilisateur
    $user = User::create([
        'username' => $request->prenom . ' ' . $request->nom,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // Vous pouvez ajouter une logique pour les rôles
        'date_de_naissance' => $request->date_de_naissance,
        'sexe' => $request->sexe,
        'age' => $age,
        'home_id' => $homeId,
    ]);
    Log::info('Redirection vers la page d\'accueil après la création de l\'utilisateur');

    return redirect()->route('home')->with('success', 'Utilisateur créé avec succès');
}

    public function store(Request $request)
    {

        // Validation des données
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',  // Validation de l'email unique
            'password' => 'required|min:6|confirmed',  // Vérifie que le mot de passe et la confirmation correspondent
            'date_de_naissance' => 'required|date',
            'sexe' => 'required|in:homme,femme,autre',
            'role' => 'required|in:invite,enfant,admin,parent',  // Profils possibles
            'home_id' => 'required|integer|max:255',
        ]);

        $dateNaissance = \Carbon\Carbon::parse($request->date_de_naissance);
        $age = $dateNaissance->age; // Calculer l'âge avec Carbon

        // Création de l'utilisateur
        $user = User::create([
            'username' => $request->prenom . ' ' . $request->nom,  // Crée le nom d'utilisateur
            'email' => $request->email,  // Email de l'utilisateur
            'password' => Hash::make($request->password),  // Sécuriser le mot de passe
            'role' => $request->role,  // Rôle de l'utilisateur (admin, parent, enfant, visiteur)
            'date_de_naissance' => $request->date_de_naissance,  // Date de naissance
            'sexe' => $request->sexe,  // Sexe de l'utilisateur
            'age' => $age,
            'home_id' => $request->home_id,  // ID de la maison associée à l'utilisateur
        ]);

        // Message de succès et redirection
        return redirect()->route('home')->with('success', 'Utilisateur créé avec succès');
    }

    public function showProfile()
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();
    
        // Passer les données de l'utilisateur connecté à la vue
        return view('profil', ['user' => $user]);
    }
    
    public function showProfileById($userId)
{
    // Récupérer les données de l'utilisateur en fonction de l'ID passé dans l'URL
    $user = User::findOrFail($userId);  // Si l'utilisateur n'existe pas, une erreur 404 sera lancée

    // Passer les données de l'utilisateur à la vue
    return view('profil', ['user' => $user]);
}


public function update(Request $request, $userId = null)
{
    // Si un ID d'utilisateur est passé en paramètre (par exemple, pour un admin qui gère un autre utilisateur)
    if ($userId) {
        // Vérifier que l'utilisateur connecté a bien les droits pour modifier cet utilisateur
        // Par exemple, vérifier si l'utilisateur est un administrateur
        if (auth()->user()->role != 'admin') {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission de modifier cet utilisateur.');
        }
        // Récupérer l'utilisateur à mettre à jour par son ID
        $user = User::findOrFail($userId);  // Si l'utilisateur n'existe pas, une erreur 404 sera lancée
    } else {
        // Si aucun ID n'est passé, mettre à jour l'utilisateur connecté
        $user = auth()->user();
    }

    // Validation des données du formulaire
    $validated = $request->validate([
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,  // Validation de l'email unique pour cet utilisateur
        'password' => 'nullable|min:6|confirmed',  // Mot de passe optionnel mais confirmé
    ]);

    // Mettre à jour les informations de l'utilisateur
    $user->username = $request->prenom . ' ' . $request->nom; // Mettre à jour le nom d'utilisateur
    $user->email = $request->email; // Mettre à jour l'email

    // Si un mot de passe est fourni, le mettre à jour
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Sauvegarder les modifications
    $user->save();

    // Redirection vers la page appropriée avec un message de succès
    if ($userId) {
        // Si on a modifié un autre utilisateur, on le redirige vers son profil
        return redirect()->route('profil.show', $user->id)->with('success', 'Profil mis à jour avec succès');
    }

    // Si on a modifié l'utilisateur connecté, on le redirige vers la page d'accueil
    return redirect()->route('home')->with('success', 'Profil mis à jour avec succès');
}




}