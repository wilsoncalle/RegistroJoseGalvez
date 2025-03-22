<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('anios_academicos', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('estado');
        });
    }

    public function down()
    {
        Schema::table('anios_academicos', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
};
