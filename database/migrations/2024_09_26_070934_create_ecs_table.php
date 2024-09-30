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
        Schema::create('ecs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_ec')->nullable();
            $table->integer('volume_et')->nullable();
            $table->integer('volume_ed')->nullable();
            $table->integer('volume_tp')->nullable();
            $table->foreignId('ue_id')->constrained()->onDelete('cascade');
            $table->foreignId('enseignant_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecs');
    }
};
