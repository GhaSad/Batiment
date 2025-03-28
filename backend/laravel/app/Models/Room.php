<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Attributs qui peuvent être assignés en masse
    protected $fillable = [
        'name',
        'description',
        'home_id', // Associe la pièce à une maison
    ];

    // Relier la pièce à la maison
    public function home()
    {
        return $this->belongsTo(Home::class);
    }

    // Room.php
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

}
