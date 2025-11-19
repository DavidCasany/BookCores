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
        Schema::create('llibres', function (Blueprint $table) {
        $table->id();
        $table->string('titol');
        $table->text('descripcio'); 
        $table->decimal('preu', 8, 2); 
        
        // Camins als arxius (Strings, no els arxius reals)
        $table->string('img_portada')->nullable(); 
        $table->string('fitxer_pdf'); 
        
        $table->foreignId('autor_id')->constrained('autors')->cascadeOnDelete();
        $table->foreignId('editorial_id')->constrained('editorials')->cascadeOnDelete();
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llibre');
    }
};
