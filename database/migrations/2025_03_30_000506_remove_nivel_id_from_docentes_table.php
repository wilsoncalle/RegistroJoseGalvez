<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Eliminar la relación (foreign key)
            $table->dropForeign(['nivel_id']); 

            // Eliminar la columna
            $table->dropColumn('nivel_id');
        });
    }

    public function down()
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Recrear la columna y relación (para revertir)
            $table->foreignId('nivel_id')
                ->nullable()
                ->constrained('niveles', 'id_nivel')
                ->onDelete('set null');
        });
    }
};