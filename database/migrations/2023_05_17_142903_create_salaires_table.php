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
        Schema::create('salaires', function (Blueprint $table) {
            $table->id();
            $table->double('nbre_heures_travail');
            $table->double('nbre_absence');
            $table->double('nbre_heure_repos');
            $table->double('salaire total');
            $table->string('etat');
            $table->string('mois');
            $table->string('année');
            $table->timestamps();
            $table->foreignId('employe_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaires');
    }
};
