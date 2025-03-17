<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->onDelete('cascade');
            $table->date('fecha');
            $table->enum('estado', ['Presente', 'Ausente', 'Tardanza', 'Justificado']);
            $table->string('observacion', 200)->nullable();
            $table->foreignId('id_asignacion')->nullable()->constrained('asignaciones', 'id_asignacion')->onDelete('set null');
            $table->unique(['id_estudiante', 'fecha', 'id_asignacion']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencia');
    }
};
