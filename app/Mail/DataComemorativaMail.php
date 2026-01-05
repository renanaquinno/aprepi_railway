<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DataComemorativaMail extends Mailable
{
    use Queueable, SerializesModels;
 
    public $user;
    public $mensagem;
   

    public function __construct(User $user, $mensagem)
    {
        $this->user = $user;
        $this->mensagem = $mensagem;
       
    }

    public function build()
    {
        return $this->subject($this->mensagem['titulo'] ?? 'Data Comemorativa')
            ->markdown('emails.data_comemorativa')
            ->with([
                'user' => $this->user,
                'mensagem' => $this->mensagem,
            ]);
    }
}
