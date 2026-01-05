<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensagemAniversario extends Model
{
    protected $table = 'mensagens_aniversario';    
    protected $fillable = ['mensagem'];
}
