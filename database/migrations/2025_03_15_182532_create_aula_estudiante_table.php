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
        Schema::create('aula_estudiante', function (Blueprint $table) {
            $table->foreignId('id_aula')->constrained('aulas', 'id_aula')->onDelete('cascade');
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->onDelete('cascade');
            $table->foreignId('id_anio')->constrained('anios_academicos', 'id_anio')->onDelete('cascade');
            $table->primary(['id_aula', 'id_estudiante', 'id_anio']); // Clave primaria compuesta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aula_estudiante');
    }
};
