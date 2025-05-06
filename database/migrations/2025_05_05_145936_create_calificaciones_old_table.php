<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear tabla con nuevo nombre calificacionesOld
        Schema::create('calificacionesOld', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->foreignId('id_estudiante')
                  ->constrained('estudiantes', 'id_estudiante')
                  ->onDelete('cascade');
            $table->foreignId('id_asignacion')
                  ->constrained('asignaciones', 'id_asignacion')
                  ->onDelete('cascade');
            $table->foreignId('id_trimestre')
                  ->constrained('trimestres', 'id_trimestre')
                  ->onDelete('cascade');

            // Comportamiento: puntuación de 0 a 20
            $table->unsignedTinyInteger('comportamiento')->comment('Comportamiento de 0 a 20');
            // Número de asignaturas reprobadas
            $table->unsignedSmallInteger('asignaturas_reprobadas')->comment('Cantidad de asignaturas reprobadas');
            // Conclusión alfabética, derivada de la nota
            $table->string('conclusion', 50)->comment('Conclusión derivada de la nota');

            $table->string('grado', 50)
                  ->constrained('grados', 'id_grados')
                  ->onDelete('cascade');
            $table->decimal('nota', 4, 2);
            $table->date('fecha');
            $table->string('observacion', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificacionesOld');
    }
};
