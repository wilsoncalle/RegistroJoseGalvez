<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anios_academicos', function (Blueprint $table) {
            $table->id('id_anio');
            $table->integer('anio')->unique();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['Planificado', 'En curso', 'Finalizado']);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anios_academicos');
    }
};
