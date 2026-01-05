<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN tipo_usuario ENUM('admin', 'voluntario_adm', 'voluntario_ext', 'socio', 'doador') DEFAULT 'socio'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN tipo_usuario ENUM('admin', 'voluntario', 'socio', 'doador') DEFAULT 'socio'");
    }
};
