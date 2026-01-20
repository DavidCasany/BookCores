<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llibres', function (Blueprint $table) {
            $table->id();
            $table->string('titol');
            $table->text('descripcio')->nullable(); 
            $table->decimal('preu', 8, 2);
            $table->integer('pagines');
            $table->string('genere');


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
