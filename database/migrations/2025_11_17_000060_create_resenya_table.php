<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CORRECCIÓ: 'ressenyes' amb dues S
        Schema::create('ressenyes', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('puntuacio'); 
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Assegura't que 'llibres' és plural, com vam corregir abans
            $table->foreignId('llibre_id')
                  ->constrained('llibres', column: 'id_llibre')
                  ->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // CORRECCIÓ: 'ressenyes' amb dues S
        Schema::dropIfExists('ressenyes');
    }
};