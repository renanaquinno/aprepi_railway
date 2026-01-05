<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\AniversarianteEnvio; 
use App\Models\MensagemAniversario; 
use App\Mail\FelizAniversarioMail;


class AniversariantesController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today()->toDateString();
        $envioHoje = AniversarianteEnvio::where('data_envio', $hoje)->exists();
        $aniversariantes = User::whereDay('data_nascimento', '=', date('d'))
                                ->whereMonth('data_nascimento', '=', date('m'))
                                ->get();
        return view('aniversariantes.index', compact('aniversariantes', 'envioHoje'));
    }

    public function enviarEmails()
    {
        $hoje = Carbon::today()->toDateString();
        $jaEnviado = AniversarianteEnvio::where('data_envio', $hoje)->exists();

        if ($jaEnviado) {
            return redirect()->back()->with('info', 'Os emails de aniversário já foram enviados hoje.');
        }

        $aniversariantes = User::whereDay('data_nascimento', '=', date('d'))
                                ->whereMonth('data_nascimento', '=', date('m'))
                                ->get();

        // Busca a última mensagem salva
        $mensagem = MensagemAniversario::latest()->first();

        // Se não existir mensagem no banco, define uma padrão
        if (!$mensagem) {
            $mensagem = new MensagemAniversario([
                'mensagem' => "Olá, {{nome}} 🎉 <br><br>
                    Desejamos a você um feliz aniversário, muita saúde, felicidade e sucesso!<br>
                    Que este novo ciclo seja repleto de realizações.<br><br>
                    Abraços da nossa equipe!"
            ]);
        }

        foreach ($aniversariantes as $usuario) {
            $mensagemFinal = str_replace('{{nome}}', $usuario->name, $mensagem->mensagem);
            Mail::to($usuario->email)->send(new FelizAniversarioMail($usuario, $mensagemFinal));
            sleep(2);
        }


        AniversarianteEnvio::create(['data_envio' => $hoje]);
        return redirect()->back()->with('success', 'Emails enviados com sucesso!');
    }


}