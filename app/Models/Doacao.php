<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogAllActivity;

class Doacao extends Model
{
    use HasFactory;
    use LogAllActivity;

    protected $table = 'doacoes'; // <- Aqui você corrige

    protected $fillable = [
        'user_id',
        'data_doacao',
        'valor',
        'forma_pagamento',
        'observacoes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}