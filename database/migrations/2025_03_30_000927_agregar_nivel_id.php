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
        Schema::table('docentes', function (Blueprint $table) {
            // Eliminar la columna id_materia si existe
            if (Schema::hasColumn('docentes', 'id_materia')) {
                $table->dropForeign(['id_materia']); // Asegúrate de adaptar el nombre de la restricción si es diferente
                $table->dropColumn('id_materia');
            }
            
            // Agregar la columna id_nivel
            $table->unsignedBigInteger('id_nivel')->nullable()->after('fecha_contratacion');
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Eliminar la columna id_nivel
            if (Schema::hasColumn('docentes', 'id_nivel')) {
                $table->dropForeign(['id_nivel']);
                $table->dropColumn('id_nivel');
            }
            
            // Restaurar la columna id_materia
            $table->unsignedBigInteger('id_materia')->nullable()->after('fecha_contratacion');
            $table->foreign('id_materia')->references('id_materia')->on('materias');
        });
    }
};