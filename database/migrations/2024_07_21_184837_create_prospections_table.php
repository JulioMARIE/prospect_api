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

        Schema::dropIfExists('prospections');

        Schema::create('prospections', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_heure');
            $table->string('personne_rencontree');
            $table->string('contact_pers_rencont');
            $table->string('fonction_pers_rencont');
            $table->string('logiciels')->nullable();
            $table->string('observations')->nullable();
            $table->foreignId('commercial_id');
            $table->foreignId('societe_id');
            $table->foreign('commercial_id')->references('id')->on('commercials')->onDelete('cascade');
            $table->foreign('societe_id')->references('id')->on('societes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospections');
    }
};
