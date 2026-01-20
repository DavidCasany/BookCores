<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llibres', function (Blueprint $table) {
           
            $table->id('id_llibre');

            
            $table->string('titol');
            $table->string('genere'); 
            $table->text('descripcio')->nullable();
            $table->decimal('preu', 8, 2);
            
            $table->decimal('nota_promig', 3, 1)->nullable(); 
            $table->string('img_hero')->nullable();

            
            $table->foreignId('autor_id')
                  ->constrained('autors')
                  ->cascadeOnDelete();

            $table->foreignId('editorial_id')
                  ->constrained('editorials')
                  ->cascadeOnDelete();

           
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