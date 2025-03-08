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
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string("nom_etab")->nullable();
            $table->string("slogan_etab")->nullable();
            $table->string("ville_etab")->nullable();
            $table->string("email_etab")->nullable();
            $table->string("mdpAppGmail_etab")->nullable();
            $table->string("descri_etab")->nullable();
            $table->string("abr_etab")->nullable();
            $table->string("codePostal_etab")->nullable();
            $table->string("numero")->nullable();
            $table->string("pays_etab")->nullable();
            $table->string("logo_name")->nullable();
            $table->string("dateCreation_etab")->nullable();
            $table->text("historique")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etablissements');
    }
};
