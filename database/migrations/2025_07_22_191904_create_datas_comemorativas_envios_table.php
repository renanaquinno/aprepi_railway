<?php

// database/migrations/xxxx_xx_xx_create_datas_comemorativas_envios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('datas_comemorativas_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_comemorativa_id')->constrained('datas_comemorativas')->onDelete('cascade');
            $table->date('data_envio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datas_comemorativas_envios');
    }
};