<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // IMPORTANT: Nom en plural 'compres'
        Schema::create('compres', function (Blueprint $table) {
            $table->id('id_compra');
            $table->decimal('total', 10, 2);

            // He unificat a 'user_id' per ser consistent amb la resta
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compres');
    }
};