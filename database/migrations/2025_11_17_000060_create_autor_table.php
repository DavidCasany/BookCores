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
        Schema::create('autor', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('autor', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('id_usuari')
                ->constrained(table: 'users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('id_llibre')
                ->constrained(table: 'llibres', column: 'id_llibre')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

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
