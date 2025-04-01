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
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');  // Associe avec la table devices
            $table->unique('device_id');  // Chaque dispositif ne peut avoir qu'une seule consommation
            $table->float('consumption'); // Consommation en kWh
            $table->foreignId('home_id')->constrained('homes')->onDelete('cascade');
            $table->timestamp('recorded_at')->nullable(); // Date et heure de la consommation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('energy_usage', function (Blueprint $table) {
            // Supprimer les clés étrangères et les contraintes uniques
            $table->dropForeign(['device_id']);
        });

        // Supprimer la table 'energy_usage'
        Schema::dropIfExists('energy_usage');
    }
};
