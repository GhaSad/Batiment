<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','type','status','home_id','room_id'];

/*
    public function user(){
        return $this->belongsTo(User::class);
    }
*/

    public function changeStatus($status){
        $this->status = $status;
        $this->save();
    }

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function energyUsage(){
        return $this->hasOne(EnergyUsage::class);
    }

    public function home(){
        return $this->belongsTo(Home::class);
    }
}
