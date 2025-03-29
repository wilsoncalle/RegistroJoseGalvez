<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id('id_estudiante');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('dni', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->foreignId('id_aula')->nullable()->constrained('aulas', 'id_aula')->onDelete('set null');
            $table->date('fecha_ingreso')->nullable();
            $table->enum('estado', ['Activo', 'Retirado', 'Egresado'])->default('Activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
};
