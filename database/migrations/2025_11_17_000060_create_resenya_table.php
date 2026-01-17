<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ressenyes', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('puntuacio')->nullable();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Relació amb llibres (assegura't que la taula es diu 'llibres' i la clau 'id_llibre')
            $table->foreignId('llibre_id')
                  ->constrained('llibres', column: 'id_llibre')
                  ->cascadeOnDelete();
            
            // AQUESTA ÉS LA CLAU PER A LES RESPOSTES
            $table->foreignId('resposta_a_id')
                  ->nullable()
                  ->constrained('ressenyes') // Es relaciona amb si mateixa
                  ->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressenyes');
    }
};