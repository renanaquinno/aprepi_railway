<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogAllActivity;

class CestaBasica extends Model
{
    use HasFactory;
    use LogAllActivity;

    protected $table = 'cestas_basicas';    

    protected $fillable = [
        'data_recebimento',
        'entrada_tipo',
        'origem',
        'status',
        'data_entrega',
        'user_id',
        'observacoes',
    ];

    public function origemPessoa()
    {
        return $this->belongsTo(User::class, 'origem');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
