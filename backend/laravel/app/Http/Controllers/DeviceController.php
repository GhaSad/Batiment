<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Device;
use App\Models\Logs;
use Faker\Factory as Faker;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Récupérer toutes les pièces avec les dispositifs associés
    $rooms = Room::with('devices')->get();
    
    // Récupérer tous les dispositifs (si nécessaire pour d'autres parties de la vue)
    $devices = Device::all();

    // Retourner la vue 'home' avec les pièces et les dispositifs
    return view('home', compact('rooms', 'devices'));
}

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

    // Initialisation de Faker
    $faker = Faker::create();

    // Création de l'objet dans la base de données
    try {
        $device = Device::create([
            'name' => $request->nom,
            'type' => $request->type, // Utilisation du type du formulaire
            'status' => 'inactif', // Valeur par défaut pour le status
            'energy_usage' => $faker->randomFloat(2, 0, 100),
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

    public function getDevicesByType($type)
    {
        // Récupérer les dispositifs filtrés par type
        $devices = Device::where('type', $type)->get();
    
        // Retourner les dispositifs sous forme de JSON
        return response()->json($devices);
    }
    
    public function getDevices()
{
    $devices = Device::all();  // Ou selon ton besoin, tu peux ajouter des conditions
    return response()->json($devices);  // Renvoie les dispositifs sous forme de JSON
}


public function updateDeviceStatus($id, Request $request)
{
    try {
        // Récupérer le dispositif par ID
        $device = Device::findOrFail($id);

        // Validation de la requête
        $validated = $request->validate([
            'status' => 'required|in:actif,inactif',
        ]);

        // Changer le statut en fonction de l'état actuel
        $newStatus = ($device->status === 'actif') ? 'inactif' : 'actif';

        // Mettre à jour le statut du dispositif
        $device->status = $newStatus;
        $device->save();

        // Créer un log de cette action
        $logMessage = "Le statut du dispositif {$device->name} a été changé en {$newStatus}";
        Logs::create([
            'user_id' => Auth::id(),  // L'utilisateur qui a effectué l'action
            'device_id' => $device->id,
            'log_message' => $logMessage,
            'status' => $newStatus,
        ]);

        // Retourner une réponse JSON
        return response()->json([
            'message' => 'Statut du dispositif mis à jour avec succès',
            'device' => $device
        ]);

    } catch (\Exception $e) {
        // Retourner une réponse d'erreur avec les détails de l'exception
        return response()->json([
            'error' => 'Erreur lors de la mise à jour du statut du dispositif',
            'details' => $e->getMessage()
        ], 500);  // Code 500 pour une erreur serveur
    }
}




}
