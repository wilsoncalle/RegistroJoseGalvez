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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id('id_aula');
            $table->foreignId('id_nivel')->constrained('niveles', 'id_nivel')->onDelete('cascade');
            $table->foreignId('id_grado')->constrained('grados', 'id_grado')->onDelete('cascade');
            $table->foreignId('id_seccion')->constrained('secciones', 'id_seccion')->onDelete('cascade');
            $table->unique(['id_nivel', 'id_grado', 'id_seccion']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
