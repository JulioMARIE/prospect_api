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

        Schema::dropIfExists('societes');

        Schema::create('societes', function (Blueprint $table) {
            $table->id();
            $table->string('denomination');
            $table->string('raison_sociale');
            $table->string('IFU');
            $table->string('description_siege');
            $table->foreignId('commune_id');
            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societes');
    }
};
