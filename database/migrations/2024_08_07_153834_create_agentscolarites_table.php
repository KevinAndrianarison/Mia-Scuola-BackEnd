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
        Schema::create('agentscolarites', function (Blueprint $table) {
            $table->id();
            $table->string("nomComplet_scol")->nullable();
            $table->integer("telephone_scol")->nullable();
            $table->string("date_recrutement_scol")->nullable();
            $table->string("categorie_scol")->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agentscolarites');
    }
};
