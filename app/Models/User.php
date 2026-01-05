<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Traits\LogAllActivity;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use LogAllActivity;
    
    protected $fillable = [
        'name',
        'data_nascimento',
        'telefone',
        'email',
        'password',
        'cpf',
        'tipo_usuario',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];

    public function doacoes()
    {
        return $this->hasMany(Doacao::class);
    }
    
    public function eventosParticipando()
    {
        return $this->belongsToMany(Evento::class, 'evento_user')->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->tipo_usuario === 'admin';
    }

    public function isVoluntarioAdm()
    {
        return $this->tipo_usuario === 'voluntario_adm';
    }

    public function getTipoUsuarioLabelAttribute()
    {
        return match($this->tipo_usuario) {
            'admin' => 'Administrador',
            'voluntario_adm' => 'Voluntário Administrativo',
            'voluntario_ext' => 'Voluntário Externo',
            'socio' => 'Sócio',
            default => ucfirst($this->tipo_usuario),
        };
    }

    public function estadias()
    {
        return $this->hasMany(Estadia::class);
    }

}

