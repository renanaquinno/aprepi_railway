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
        Schema::create('cestas_basicas', function (Blueprint $table) {
        $table->id();
        $table->date('data_recebimento');
        $table->enum('entrada_tipo', ['doacao', 'compra']);
        $table->foreignId('origem')->nullable()->constrained('users')->onDelete('set null');
        $table->enum('status', ['disponivel', 'entregue'])->default('disponivel');
        $table->date('data_entrega')->nullable();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->text('observacoes')->nullable();
        $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cestas_basicas');
    }
};
