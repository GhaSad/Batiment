<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnergyController extends Controller
{
    public function showEnergyUsage(){
        
        $devices = Device::with(['energyUsage'])->get();

        return view('home',compact('devices'));
    }
}
