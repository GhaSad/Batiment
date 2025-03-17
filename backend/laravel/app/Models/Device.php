<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','type','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
