<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->onDelete('cascade');
            $table->foreignId('id_asignacion')->constrained('asignaciones', 'id_asignacion')->onDelete('cascade');
            $table->foreignId('id_trimestre')->constrained('trimestres', 'id_trimestre')->onDelete('cascade');
            $table->string('grado', 50)->constrained('grados', 'id_grados')->onDelete('cascade');;
            $table->decimal('nota', 4, 2);
            $table->date('fecha');
            $table->string('observacion', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calificaciones');
    }
};