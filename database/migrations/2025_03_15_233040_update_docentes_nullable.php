<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Mantener 'nombre' y 'apellido' requeridos
            $table->string('nombre', 50)->nullable(false)->change();
            $table->string('apellido', 50)->nullable(false)->change();

            // Hacer que todas las demás columnas sean opcionales
            $table->string('dni', 20)->nullable()->change();
            $table->date('fecha_nacimiento')->nullable()->change();
            $table->string('direccion', 200)->nullable()->change();
            $table->string('telefono', 20)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
            $table->date('fecha_contratacion')->nullable()->change();
            $table->unsignedBigInteger('id_materia')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Revertir cambios si se necesita deshacer la migración
            $table->string('nombre', 50)->nullable(false)->change();
            $table->string('apellido', 50)->nullable(false)->change();
            $table->string('dni', 20)->nullable(false)->change();
            $table->date('fecha_nacimiento')->nullable(false)->change();
            $table->string('direccion', 200)->nullable(false)->change();
            $table->string('telefono', 20)->nullable(false)->change();
            $table->string('email', 100)->nullable(false)->change();
            $table->date('fecha_contratacion')->nullable(false)->change();
            $table->unsignedBigInteger('id_materia')->nullable(false)->change();
        });
    }
};
