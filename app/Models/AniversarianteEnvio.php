<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AniversarianteEnvio extends Model
{
    protected $table = 'aniversariantes_envios';
    protected $fillable = ['data_envio'];
}
