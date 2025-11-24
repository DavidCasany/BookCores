<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llibres', function (Blueprint $table) {
            $table->id('id_llibre'); // O $table->id();
            $table->string('titol');
            $table->text('descripcio')->nullable();
            $table->decimal('preu', 8, 2);
            
            // AQUESTA ÉS LA QUE ET FALTAVA:
            $table->float('nota_promig')->default(0); 

            $table->string('img_portada')->nullable();
            $table->string('fitxer_pdf')->nullable(); // He posat nullable per si de cas

            $table->foreignId('autor_id')->constrained('autors')->cascadeOnDelete();
            $table->foreignId('editorial_id')->constrained('editorials')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llibres'); // Corregit a plural
    }
};