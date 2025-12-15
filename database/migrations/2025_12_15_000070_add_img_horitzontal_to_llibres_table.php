<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Comprovem si la columna ja existeix per no donar error
        if (!Schema::hasColumn('llibres', 'img_hero')) {
            Schema::table('llibres', function (Blueprint $table) {
                $table->string('img_hero')->nullable()->after('img_portada');
            });
        }
    }

    public function down(): void
    {
        Schema::table('llibres', function (Blueprint $table) {
            $table->dropColumn('img_hero');
        });
    }
};
