<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aula_docente', function (Blueprint $table) {
            $table->foreignId('id_aula')->constrained('aulas', 'id_aula')->onDelete('cascade');
            $table->foreignId('id_docente')->constrained('docentes', 'id_docente')->onDelete('cascade');
            $table->foreignId('id_materia')->constrained('materias', 'id_materia')->onDelete('cascade');
            $table->foreignId('id_anio')->constrained('anios_academicos', 'id_anio')->onDelete('cascade');
            $table->primary(['id_aula', 'id_docente', 'id_materia', 'id_anio']); // Clave primaria compuesta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('aula_docente', function (Blueprint $table) {
            $table->dropForeign(['id_docente']); // Elimina la clave foránea
        });
    
        Schema::dropIfExists('aula_docente'); // Elimina la tabla
    }
};
