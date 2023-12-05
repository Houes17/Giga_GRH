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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            //$table->string('presence');
            $table->string('observation')->nullable();
            $table->string('date');
            $table->string('effectuer par')->nullable();
            // ajout des clé etrangers//
            $table->foreignId('site_id')->nullable();
            $table->foreignId('employe_id');
            $table->foreignId('faction_id');
            $table->double('salairepresence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
