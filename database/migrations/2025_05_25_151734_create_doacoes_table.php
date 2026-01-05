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
    Schema::create('doacoes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->date('data_doacao');
        $table->decimal('valor', 10, 2);
        $table->string('forma_pagamento'); // exemplo: pix, boleto, transferência
        $table->text('observacoes')->nullable();
        $table->enum('status', ['realizada', 'pendente', 'cancelada'])->default('realizada');
        $table->timestamps();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacoes');
    }
};
