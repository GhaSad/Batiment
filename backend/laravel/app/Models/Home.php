<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Home extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class); // Une maison peut avoir plusieurs utilisateurs
    }

    public function rooms()
    {
        return $this->hasMany(Room::class); // Une maison peut avoir plusieurs pièces
    }

    public function devices()
    {
        return $this->hasMany(Device::class); // Une maison peut avoir plusieurs appareils
    }

    public function energyUsages()
    {
        return $this->hasMany(EnergyUsage::class); // Une maison peut avoir plusieurs enregistrements de consommation d'énergie
    }
}
