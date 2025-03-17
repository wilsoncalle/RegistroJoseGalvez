<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grados', function (Blueprint $table) {
            $table->id('id_grado');
            $table->string('nombre', 50);
            $table->foreignId('id_nivel')->constrained('niveles', 'id_nivel')->onDelete('cascade');
            $table->unique(['nombre', 'id_nivel']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grados');
    }
};
