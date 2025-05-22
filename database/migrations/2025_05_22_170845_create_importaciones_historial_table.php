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
        Schema::create('importaciones_historial', function (Blueprint $table) {
            $table->id('id_importacion');
            $table->string('nombre_archivo', 255)->comment('Nombre del archivo que se importó');
            $table->string('anio_academico', 50)->nullable()->comment('Año académico seleccionado');
            $table->unsignedBigInteger('id_nivel')->nullable()->comment('ID del nivel educativo');
            $table->unsignedBigInteger('id_aula')->nullable()->comment('ID del aula seleccionada');
            $table->string('nivel_nombre', 100)->nullable()->comment('Nombre del nivel (para mantener el historial aunque cambie)');
            $table->string('aula_nombre', 255)->nullable()->comment('Nombre completo del aula incluyendo grado y sección');
            $table->date('fecha_importacion')->comment('Fecha en que se realizó la importación');
            $table->integer('total_importados')->default(0)->comment('Cantidad de estudiantes importados');
            $table->string('usuario', 100)->nullable()->comment('Usuario que realizó la importación');
            $table->timestamps();
            
            // Restricciones de clave foránea
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles')
                  ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('id_aula')->references('id_aula')->on('aulas')
                  ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importaciones_historial');
    }
};
