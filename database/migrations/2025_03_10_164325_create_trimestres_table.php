<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id('id_trimestre');
            $table->string('nombre', 50);
            $table->foreignId('id_anio')->constrained('anios_academicos', 'id_anio')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unique(['nombre', 'id_anio']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trimestres');
    }
};
