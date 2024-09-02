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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('nomComplet_etud')->nullable();
            $table->string('date_naissance_etud')->nullable();
            $table->string('adresse_etud')->nullable();
            $table->integer('telephone_etud')->nullable();
            $table->string('matricule_etud')->nullable();
            $table->string('nom_mere_etud')->nullable();
            $table->string('nom_pere_etud')->nullable();
            $table->string('sexe_etud')->nullable();
            $table->string("validiter_inscri")->nullable();
            $table->integer('CIN_etud')->nullable();
            $table->integer('nom_tuteur')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
