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
        Schema::create('energy_usage', function (Blueprint $table) {
            $table->id();
            $table->unique('device_id');  // Chaque dispositif ne peut avoir qu'une seule consommation
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->float('consumption');
            $table->foreignId('home_id')->constrained('homes')->onDelete('cascade');
            $table->timestamp('recorded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('energy_usage', function (Blueprint $table) {
            // Supprimer la contrainte unique et la clé étrangère si nécessaire
            $table->dropForeign(['device_id']);
            $table->dropUnique(['device_id']);
        });
    }
};
