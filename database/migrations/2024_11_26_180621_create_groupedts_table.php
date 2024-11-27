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
        Schema::create('groupedts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semestre_id')->constrained();
            $table->foreignId('parcour_id')->constrained();
            $table->foreignId('au_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupedts');
    }
};
