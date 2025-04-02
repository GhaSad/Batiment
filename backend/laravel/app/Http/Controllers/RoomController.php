<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class RoomController extends Controller
{
    // Méthode pour ajouter une pièce
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $faker = Faker::create();

        // Créer une nouvelle pièce associée à la maison de l'utilisateur
        $room = new Room();
        $room->name = $request->nom;
        $room->heating_consumption = $faker->randomFloat(2, 0, 100);
        $room->home_id = auth()->user()->home_id; // Associe la pièce à la maison de l'utilisateur
        $room->save();

        // Retourner la pièce créée comme réponse JSON
        return response()->json($room);
    }

    // Méthode pour récupérer toutes les pièces
    public function index()
{
    // Charger les pièces et leurs objets connectés associés
    $rooms = Room::with('devices')->where('home_id', auth()->user()->home_id)->get();

    return response()->json($rooms); // Renvoie les pièces avec leurs dispositifs associés
}

    

}
