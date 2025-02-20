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
        Schema::create('congepermissions', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('dateDebut')->nullable();
            $table->string('dateFin')->nullable();
            $table->string('fichier_nom')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string("status")->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('congepermissions');
    }
};
