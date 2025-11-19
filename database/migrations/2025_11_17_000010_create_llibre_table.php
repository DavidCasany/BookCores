<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void
    {
        Schema::create('llibres', function (Blueprint $table) {
            $table->id('id_llibre');
            $table->string('nom');
            $table->string('img_portada')->nullable();
            $table->decimal('preu', 8, 2)->default(0);
            $table->float('nota_promig')->default(0);
            $table->string('fitxer_pdf')->nullable();

            
            $table->foreignId('id_autor')
                ->constrained(table: 'autors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            
            $table->foreignId('id_editorial')
                ->constrained(table: 'editorials', column: 'id_editorial')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
