<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyUsage extends Model
{
    use HasFactory;

    // Si nécessaire, spécifie les colonnes que tu veux permettre pour l'assignation de masse
    protected $fillable = ['device_id', 'consumption', 'recorded_at'];

    // Relation avec le modèle Device
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function Home(){
        return $this->belongsTo(Home::class);
    }
}
