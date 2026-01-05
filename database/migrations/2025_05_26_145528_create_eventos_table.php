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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->dateTime('data_hora');
            $table->string('local');
            $table->decimal('valor_custo', 10, 2)->default(0);
            $table->decimal('valor_arrecadado', 10, 2)->default(0);
            $table->boolean('recorrente')->default(false);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
