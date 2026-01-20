<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('compra_llibre', function (Blueprint $table) {
            $table->id();
            
            
            $table->foreignId('compra_id')
                  ->constrained('compres', column: 'id_compra')
                  ->cascadeOnDelete();
            
           
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