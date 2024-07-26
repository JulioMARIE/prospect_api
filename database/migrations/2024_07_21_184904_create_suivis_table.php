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

        Schema::dropIfExists('suivis');

        Schema::create('suivis', function (Blueprint $table) {
            $table->id();
            $table->datetime('date_heure');
            $table->string('observation');
            $table->foreignId('prospection_id');
            $table->foreign('prospection_id')->references('id')->on('prospections')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivis');
    }
};
