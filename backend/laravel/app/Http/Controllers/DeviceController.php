<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Log;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all();
        return view('devices.index', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation des données

        $request->validate([
            'name' => 'required|string|max:255',
            'tpye' => 'required|in:thermostat,lumière,caméra,capteur,autre',
            'status' => 'required|in:actif,inactif',
            'home_id' => 'required|exists:homes,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $device = Device::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
            'home_id' => $request->home_id,
            'room_id' => $request->room_id,
        ]);

        Log::create([
            'user_id' =>auth()->id(), //Utilisateur actuel
            'device_id' => $device->id,
            'log_message' => "Dispositif créé : " . $device->name,
            'status' => $device->status,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $device = Device::find($id);
        return response()->json($device);
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
