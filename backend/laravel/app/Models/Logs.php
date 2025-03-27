<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    // Ajoutez ici les colonnes autorisées pour l'attribution en masse
    protected $fillable = ['user_id', 'device_id', 'log_message', 'status']; // Ajoutez user_id ici

    // Si vous souhaitez masquer certains champs (comme le mot de passe), vous pouvez les ajouter ici
    protected $hidden = ['user_id']; // Si vous ne voulez pas exposer le user_id directement

    // Si vous avez besoin de définir d'autres propriétés ou méthodes, vous pouvez les ajouter ci-dessous
}
