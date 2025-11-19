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
        Schema::create('resenyes', function (Blueprint $table) {
        $table->id();
        $table->text('text');
        $table->integer('puntuacio'); // De l'1 al 5
        
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('llibre_id')->constrained('llibres')->cascadeOnDelete();
        
        $table->timestamps(); // created_at serveix com a data_publicació
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenya');
    }
};
