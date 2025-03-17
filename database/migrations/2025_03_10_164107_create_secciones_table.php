<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->id('id_seccion');
            $table->string('nombre', 10);
            $table->foreignId('id_grado')->constrained('grados', 'id_grado')->onDelete('cascade');
            $table->unique(['nombre', 'id_grado']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('secciones');
    }
};

