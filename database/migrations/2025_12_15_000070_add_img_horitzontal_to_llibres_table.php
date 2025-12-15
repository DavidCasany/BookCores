<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('llibres', function (Blueprint $table) {
            // Afegeix la columna després de img_portada
            $table->string('img_hero')->nullable()->after('img_portada');
        });
    }

    public function down(): void
    {
        Schema::table('llibres', function (Blueprint $table) {
            $table->dropColumn('img_hero');
        });
    }
};
