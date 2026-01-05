<?php
namespace App\Http\Controllers;

use App\Models\MensagemAniversario;
use Illuminate\Http\Request;

class MensagemAniversarioController extends Controller
{
    public function edit()
    {
        // Pega a última mensagem salva, ou inicializa
        $mensagem = MensagemAniversario::latest()->first();

        if (!$mensagem) {
            $mensagem = new MensagemAniversario([
                'mensagem' => "Olá, {{nome}} 🎉 <br><br>
                    Desejamos a você um feliz aniversário, muita saúde, felicidade e sucesso!<br>
                    Que este novo ciclo seja repleto de realizações.<br><br>
                    Abraços da nossa equipe!"
            ]);
        }

        return view('aniversariantes.mensagem', compact('mensagem'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'mensagem' => 'required|string',
        ]);

        // Salva como "nova versão" (mantendo histórico, se quiser)
        MensagemAniversario::create(['mensagem' => $request->mensagem]);

        return redirect()->route('aniversariantes.index')->with('success', 'Mensagem de aniversário atualizada!');
    }
}
