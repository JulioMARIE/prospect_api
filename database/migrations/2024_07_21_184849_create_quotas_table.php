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

        Schema::dropIfExists('quotas');

        Schema::create('quotas', function (Blueprint $table) {
            $table->id();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_fixe');
            $table->foreignId('commercial_id');
            $table->foreign('commercial_id')->references('id')->on('commercials')->onDelete('cascade');
            $table->unique(['commercial_id', 'date_debut', 'date_fin']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotas');
    }
};
