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
        Schema::create('employe_dotation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dotation_id')->constrained('dotations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_dotation');
    }
};
