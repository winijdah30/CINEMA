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
        Schema::create('anime_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->constrained()->onDelete('cascade'); // Référence à la table movies
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('adult')->default(0);   // Quantité des tickets adultes
            $table->integer('etudiant')->default(0); // Quantité des tickets étudiants
            $table->integer('enfant')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_order');
    }
};
