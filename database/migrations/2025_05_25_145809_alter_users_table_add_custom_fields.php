<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->date('data_nascimento')->nullable();
        $table->string('telefone')->nullable();
        $table->string('cpf')->unique()->after('password');
        $table->enum('tipo_usuario', ['admin', 'voluntario_adm', 'voluntario_ext', 'socio', 'doador'])->default('socio');
        $table->string('endereco')->nullable();
        $table->string('cidade')->nullable();
        $table->string('estado')->nullable();
        $table->string('cep')->nullable();
        $table->text('observacoes')->nullable();
        $table->boolean('ativo')->default(true);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
