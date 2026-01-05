<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataComemorativa extends Model
{
	protected $table = 'datas_comemorativas';
    protected $fillable = ['titulo', 'mensagem', 'imagem', 'data'];

    public function envios()
    {
        return $this->hasMany(DataComemorativaEnvio::class);
    }
}
