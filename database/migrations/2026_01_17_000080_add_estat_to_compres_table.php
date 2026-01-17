<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('compres', function (Blueprint $table) {
        // Afegim la columna estat. Per defecte serà 'en_proces'
        $table->string('estat')->default('en_proces')->after('total');
    });
}

public function down()
{
    Schema::table('compres', function (Blueprint $table) {
        $table->dropColumn('estat');
    });
}
};
