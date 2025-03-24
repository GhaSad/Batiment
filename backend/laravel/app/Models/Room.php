<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Si nécessaire, spécifie les colonnes que tu veux permettre pour l'assignation de masse
    protected $fillable = ['name', 'description'];

    // Si tu as des relations avec d'autres modèles, tu peux les définir ici, par exemple :
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function home(){
        return $this->belongsTo(Home::class);
    }
}
