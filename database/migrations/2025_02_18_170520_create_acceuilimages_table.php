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
        Schema::create('acceuilimages', function (Blueprint $table) {
            $table->id();
            $table->string("photoNameOne")->nullable();
            $table->string("photoNameTwo")->nullable();
            $table->string("photoNameThree")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceuilimages');
    }
};
