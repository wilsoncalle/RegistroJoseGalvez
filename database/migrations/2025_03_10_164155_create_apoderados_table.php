<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('apoderados', function (Blueprint $table) {
            $table->id('id_apoderado');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('dni', 20)->unique()->nullable();
            $table->string('relacion', 50)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apoderados');
    }
};