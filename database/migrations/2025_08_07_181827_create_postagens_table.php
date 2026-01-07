<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostagensTable extends Migration
{
    public function up()
    {
        Schema::create('postagens', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('conteudo');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->date('data_publicacao');
            $table->string('hora_publicacao');
            $table->string('imagem')->nullable();
            $table->enum('status', ['rascunho', 'publicado']);
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // autor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('postagens');
    }
}
