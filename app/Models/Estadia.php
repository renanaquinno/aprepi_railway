<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estadia extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'data_inicio', 'data_fim', 'observacoes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}

