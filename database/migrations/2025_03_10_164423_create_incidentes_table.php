<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id('id_incidente');
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->onDelete('cascade');
            $table->date('fecha');
            $table->enum('tipo', ['Disciplina', 'Salud', 'Otro']);
            $table->text('descripcion');
            $table->foreignId('id_usuario_registro')->constrained('usuarios', 'id_usuario');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidentes');
    }
};
