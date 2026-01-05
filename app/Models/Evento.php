<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogAllActivity;
use Spatie\Activitylog\Models\Activity;

class Evento extends Model
{
    use HasFactory;

    use LogAllActivity;

    protected $fillable = [
        'titulo',
        'data_hora',
        'local',
        'valor_custo',
        'valor_arrecadado',
        'recorrente',
        'descricao'
    ];

    public $skipLog = false;


    public function participantes()
    {
        return $this->belongsToMany(User::class, 'evento_user')->withTimestamps();
    }

    /**
     * Adiciona os participantes atuais ao log automático.
     * 
     * @param Activity $activity
     * @param string $eventName
     * @return void
     */
    public function tapActivity(Activity $activity, string $eventName)
        {
            $participantesAtual = $this->participantes()->pluck('name')->toArray();
            $participantesAntigos = $this->participantes_nomes ?? [];

            $properties = $activity->properties->toArray();
            
            $properties['old']['participantes'] = $participantesAntigos;
            $properties['attributes']['participantes'] = $participantesAtual;

            $activity->properties = $properties;

        }

}
