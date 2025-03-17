<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->date('fecha_registro')->default(DB::raw('CURRENT_DATE'));
            $table->boolean('activo')->default(true);
            $table->rememberToken(); // Para funcionalidad "recordarme" de Laravel
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
