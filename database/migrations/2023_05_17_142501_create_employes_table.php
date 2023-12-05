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
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->string('nomprenoms');
            $table->string('telephone');
            $table->string('cin')->nullable();
            $table->string('statut');
            $table->date('date engagement');
            $table->date('datefinengagement')->nullable();
            $table->string('lieu naissance')->nullable();
            $table->date('date naissance')->nullable();
            $table->string('situation')->nullable();
            $table->string('photo')->nullable();
            //$table->double('salaire');
            $table->string('adresse')->nullable();
            $table->string('genre');
            $table->string('document')->nullable();
            $table->string('categorie');
            $table->timestamps();
            $table->foreignId('departement_id');
            //$table->foreignId('ville_id');
            $table->string('contrat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
