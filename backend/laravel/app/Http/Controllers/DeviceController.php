<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Device;
use App\Models\Logs;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Récupérer toutes les pièces
    $rooms = Room::all();
    
    // Récupérer tous les dispositifs
    $devices = Device::all();

    // Retourner la vue 'home' avec les pièces et les dispositifs
    return view('home', compact('rooms', 'devices'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'type' => 'required|in:porte,fenetre,alarme,lumiere,tele,enceinte,appareil,aspirateur,tondeuse,prise,arrosage,thermostat,volet,serrure,lave_linge,lave_vaisselle,four,autre', // Vous validez le type de dispositif
        'piece_id' => 'required|exists:rooms,id', // Valider que l'ID de la pièce existe
    ]);

    // Vérifiez si l'utilisateur est authentifié avant de procéder à la création
    $home_id = auth()->user()->home_id; // Récupérer l'ID de la maison de l'utilisateur connecté

    // Création de l'objet dans la base de données
    try {
        $device = Device::create([
            'name' => $request->nom,
            'type' => $request->type, // Utilisation du type du formulaire
            'status' => 'inactif', // Valeur par défaut pour le status
            'home_id' => $home_id, // Utilisation de l'ID de la maison de l'utilisateur connecté
            'room_id' => $request->piece_id, // Utilisation de l'ID de la pièce sélectionnée dans le formulaire
        ]);

        // Enregistrement dans les logs
        Logs::create([
            'user_id' => auth()->id(), // Récupérer l'ID de l'utilisateur connecté
            'device_id' => $device->id, // ID du dispositif créé
            'log_message' => "Dispositif créé : " . $device->name, // Message du log
            'status' => 'inactif', // État par défaut
        ]);

        return redirect()->route('home')->with('success', 'Dispositif créé avec succès');
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la création du dispositif', 'message' => $e->getMessage()]);
    }
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:actif,inactif',
        ]);

        //Trouver le dispositif par ID
        $device = Device::find($id);

        //Mettre a jour l'etat du dispositif
        $device->status = $request->status;
        $device->save();

        //Enregistrer l'action dans les logs
        Log::create([
            'user_id' => auth()->id(),
            'device_id' => $device->id,
            'log_message' => "Etat du dispositif mis à jour : " . $device->name,
            'status' => $device->status,
        ]);

        return response()->json(['success' => true, 'device' =>$device]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $device = Device::find($id);
        
        if ($device) {
            // Supprimer le dispositif
            $device->delete();

            // Enregistrer l'action dans les logs
            Log::create([
                'user_id' => auth()->id(),
                'device_id' => $device->id,
                'log_message' => "Dispositif supprimé : " . $device->name,
                'status' => 'supprimé',
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Dispositif non trouvé'], 404);
    }
}
