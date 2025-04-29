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
        Schema::create('client_movie', function (Blueprint $table) {
            $table->primary(['movie_id', 'client_id']);
            $table->foreignId('movie_id')->constrained();
            $table->foreignId('client_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_movie');
    }
};
