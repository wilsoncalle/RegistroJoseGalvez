<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estudiante_apoderado', function (Blueprint $table) {
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->onDelete('cascade');
            $table->foreignId('id_apoderado')->constrained('apoderados', 'id_apoderado')->onDelete('cascade');
            $table->primary(['id_estudiante', 'id_apoderado']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estudiante_apoderado');
    }
};
