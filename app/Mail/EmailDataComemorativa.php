<?php
namespace App\Mail;

use App\Models\DataComemorativa;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailDataComemorativa extends Mailable
{
    use Queueable, SerializesModels;

    public $dataComemorativa;
    public $usuario;

    public function __construct(DataComemorativa $dataComemorativa, User $usuario)
    {
        $this->dataComemorativa = $dataComemorativa;
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject($this->dataComemorativa->titulo)
                    ->markdown('emails.data_comemorativa');
    }
}
