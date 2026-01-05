<?php
namespace App\Http\Controllers;

use App\Models\DataComemorativa;
use App\Models\DataComemorativaEnvio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailDataComemorativa;
use Carbon\Carbon;

class DatasComemorativasController extends Controller
{
 
    public function index()
    {
        $datas = DataComemorativa::with('envios')
                    ->orderBy('data', 'asc')
                    ->paginate(10); 

        return view('datas_comemorativas.index', compact('datas'));
    }


    public function create()
    {
        return view('datas_comemorativas.form');
    }

    public function edit($id)
    {
        $data = DataComemorativa::findOrFail($id);
        return view('datas_comemorativas.form', compact('data'));
    }

    public function show(DataComemorativa $datas_comemorativa)
    {
        return view('datas_comemorativas.show', compact('datas_comemorativa'));
    }

    public function destroy($id)
    {
        $data = DataComemorativa::findOrFail($id);
        $data->delete();
        return redirect()->route('datas_comemorativas.index')->with('success', 'Data comemorativa excluída com sucesso!');
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'mensagem' => 'required|string',
            'imagem' => 'nullable|image|max:2048',
            'data' => 'required|date',
        ]);

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('public/datas_comemorativas');
            $data['imagem'] = str_replace('public/', 'storage/', $path);
        }

        DataComemorativa::create($data);
        return redirect()->route('datas_comemorativas.index')->with('success', 'Data comemorativa criada.');
    }

    public function enviarEmails(DataComemorativa $dataComemorativa)
    {
        $usuarios = User::all();

       foreach ($usuarios as $usuario) {
            $mensagemFinal = [
                'titulo' => $dataComemorativa->titulo,
                'mensagem' => $dataComemorativa->mensagem
            ];

            Mail::to($usuario->email)->send(new \App\Mail\DataComemorativaMail($usuario, $mensagemFinal));

            // evita bloqueio do servidor de e-mail
            sleep(2);
        }
        //$usuario = $usuarios->first(); // pega o primeiro usuário

        // if ($usuario) {
        //     $mensagemFinal = [
        //         'titulo' => $dataComemorativa->titulo,
        //         'mensagem' => $dataComemorativa->mensagem
        //     ];

        //     Mail::to($usuario->email)->send(new \App\Mail\DataComemorativaMail($usuario, $mensagemFinal));
        // }


        // Atualiza o último envio
        $dataComemorativa->update([
            'ultimo_envio' => now()
        ]);

        // Salva no histórico
        DataComemorativaEnvio::create([
            'data_comemorativa_id' => $dataComemorativa->id,
            'data_envio' => now()
        ]);

        return redirect()->route('datas_comemorativas.index')
                         ->with('success', 'Mensagem enviada para todos os usuários!');
    }
}
