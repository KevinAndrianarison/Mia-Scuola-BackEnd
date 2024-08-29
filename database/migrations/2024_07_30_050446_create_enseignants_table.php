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
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->string("nomComplet_ens")->nullable();
            $table->integer("telephone_ens")->nullable();
            $table->string("date_recrutement_ens")->nullable();
            $table->string("grade_ens")->nullable();
            $table->string("categorie_ens")->nullable();
            $table->string("chefMention_status")->nullable();
            $table->string("chefParcours_status")->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
