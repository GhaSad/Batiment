<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersAction extends Model
{
    use HasFactory;

    // Si nécessaire, spécifie les colonnes que tu veux permettre pour l'assignation de masse
    protected $fillable = ['user_id', 'device_id', 'action_type', 'value', 'action_time'];

    // Si tu veux définir des relations avec d'autres modèles
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
