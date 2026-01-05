<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Postagem extends Model
{
    use HasFactory;
    protected $table = 'postagens';

    protected $fillable = [
        'titulo',
        'conteudo',
        'categoria',
        'publicado_em',
        'status',
        'slug',
        'user_id',
    ];

     protected $casts = [
        'publicado_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}