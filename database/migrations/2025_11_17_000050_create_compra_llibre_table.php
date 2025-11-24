<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // FIXA'T EN EL NOM DE LA TAULA AQUÍ BAIX:
        Schema::create('compra_llibre', function (Blueprint $table) {
            $table->id();
            
            // Unim la Compra (el tiquet)
            $table->foreignId('compra_id')
                  ->constrained('compres', column: 'id_compra')
                  ->cascadeOnDelete();
            
            // Unim el Llibre (el producte)
            $table->foreignId('llibre_id')
                  ->constrained('llibres', column: 'id_llibre')
                  ->cascadeOnDelete();

            $table->integer('quantitat')->default(1);
            $table->decimal('preu_unitari', 10, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_llibre');
    }
};