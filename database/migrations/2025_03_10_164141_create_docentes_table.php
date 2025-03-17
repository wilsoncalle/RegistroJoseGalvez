<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('docentes', function (Blueprint $table) {
        $table->id('id_docente');
        $table->string('nombre', 50);
        $table->string('apellido', 50);
        $table->string('dni', 20)->nullable();
        $table->date('fecha_nacimiento')->nullable();
        $table->string('direccion', 200)->nullable();
        $table->string('telefono', 20)->nullable();
        $table->string('email', 100)->nullable();
        $table->date('fecha_contratacion')->nullable();
        $table->unsignedBigInteger('id_materia'); 
        $table->foreign('id_materia')->references('id_materia')->on('materias')->onDelete('cascade');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('docentes');
    }
};
