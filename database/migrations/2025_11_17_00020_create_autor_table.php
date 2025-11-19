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
    Schema::create('autors', function (Blueprint $table) {
        $table->id();
        $table->string('nom'); 
        $table->text('biografia')->nullable();
        
        // Això permet crear autors que NO siguin usuaris registrats.
        $table->foreignId('user_id')
              ->nullable() 
              ->constrained()
              ->onDelete('set null'); // Si l'usuari s'esborra, l'autor es manté però es desvincula.
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autor');
    }
};
