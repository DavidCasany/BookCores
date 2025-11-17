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
        Schema::create('compra', function (Blueprint $table) {
            $table->id('id_compra');
            $table->decimal('total', 10, 2);

            // Relació 1-N amb Usuaris (1 usuari fa N compres)
            $table->foreignId('id_usuari')
                ->constrained(table: 'users') // Taula 'users' de Laravel
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps(); // Desa la data de la comanda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra');
    }
};
