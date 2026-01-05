<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Adiciona novo campo datetime
            $table->dateTime('publicado_em')->nullable()->after('status');

            // Remove os campos antigos
            $table->dropColumn(['data_publicacao', 'hora_publicacao']);
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Volta os campos antigos
            $table->date('data_publicacao')->nullable()->after('categoria_id');
            $table->string('hora_publicacao')->nullable()->after('data_publicacao');

            // Remove o campo novo
            $table->dropColumn('publicado_em');
        });
    }
};
