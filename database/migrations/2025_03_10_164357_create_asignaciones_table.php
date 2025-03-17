<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->foreignId('id_docente')->constrained('docentes', 'id_docente')->onDelete('cascade');
            $table->foreignId('id_materia')->constrained('materias', 'id_materia');
            $table->foreignId('id_aula')->constrained('aulas', 'id_aula');
            $table->foreignId('id_anio')->constrained('anios_academicos', 'id_anio');
            $table->unique(['id_docente', 'id_materia', 'id_aula', 'id_anio']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('aula_docente', function (Blueprint $table) {
            $table->dropForeign(['id_docente']); // Elimina la clave foránea
        });
    
        Schema::dropIfExists('aula_docente'); // Elimina la tabla
    }
};
