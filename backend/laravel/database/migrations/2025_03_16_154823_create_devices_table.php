<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type',['porte','fenetre','alarme','lumiere','tele','enceinte','appareil','aspirateur','tondeuse','prise','arrosage','thermostat','volet','serrure','lave_linge','lave_vaisselle','four','autre']);
            $table->enum('status',['actif','inactif']);
            $table->float('energy_usage')->nullable();
            //Clé etrangere
            $table->foreignId('home_id')->constrained('homes')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
