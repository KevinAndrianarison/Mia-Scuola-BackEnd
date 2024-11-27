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
        Schema::create('edts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jour_id')->constrained();
            $table->foreignId('heure_id')->constrained();
            $table->foreignId('enseignant_id')->constrained();
            $table->foreignId('salle_id')->constrained();
            $table->foreignId('ec_id')->constrained();
            $table->foreignId('groupedt_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edts');
    }
};
