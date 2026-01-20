<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llibres', function (Blueprint $table) {
            // 1. ID PERSONALITZAT
            $table->id('id_llibre');

            // 2. DADES DEL LLIBRE
            $table->string('titol');
            $table->string('genere'); 
            $table->text('descripcio')->nullable();
            $table->decimal('preu', 8, 2);
            

            // 3. CAMPS EXTRES (Nota i Hero)
            $table->decimal('nota_promig', 3, 1)->nullable(); 
            $table->string('img_hero')->nullable();

            // 4. RELACIONS (Amb cascada)
            $table->foreignId('autor_id')
                  ->constrained('autors')
                  ->cascadeOnDelete();

            $table->foreignId('editorial_id')
                  ->constrained('editorials')
                  ->cascadeOnDelete();

            // 5. FITXERS
            $table->string('img_portada')->nullable();
            $table->string('fitxer_pdf')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llibres');
    }
};