<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FelizAniversarioMail extends Mailable 
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
        return $this->subject('Feliz Aniversário!')
        ->markdown('emails.feliz_aniversario')
        ->with([
            'mensagem' => $this->mensagem,
            'user' => $this->user,
        ]);
    }
}