<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataComemorativaEnvio extends Model
{
    protected $table = 'datas_comemorativas_envios';
    protected $fillable = ['data_comemorativa_id', 'data_envio'];

    public function dataComemorativa()
    {
        return $this->belongsTo(DataComemorativa::class);
    }
}
