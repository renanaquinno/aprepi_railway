<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::query();

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('data')) {
            $query->whereDate('data_hora', $request->data);
        }

        if ($request->filled('local')) {
            $query->where('local', 'like', '%' . $request->local . '%');
        }

        if ($request->filled('recorrente')) {
            $query->where('recorrente', $request->recorrente);
        }

        $eventos = $query->orderBy('data_hora', 'desc')->paginate(10);

        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $usuarios = User::whereIn('tipo_usuario', ['voluntario_adm', 'voluntario_ext'])
            ->orderBy('name')
            ->get();
        return view('eventos.form', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'data_hora' => 'required|date',
            'local' => 'required|string',
            'valor_custo' => 'required|numeric',
            'valor_arrecadado' => 'nullable|numeric',
            'recorrente' => 'boolean',
            'descricao' => 'nullable|string',
            'participantes' => 'nullable|array',
        ]);

        $evento = Evento::create($request->except('participantes'));

        if ($request->has('participantes')) {
            $evento->participantes()->sync($request->participantes);

            // Loga os participantes adicionados
            activity()
                ->performedOn($evento)
                ->causedBy(auth()->user())
                ->withProperties([
                    'participantes_adicionados' => User::whereIn('id', $request->participantes)->pluck('name'),
                ])
                ->log('Participantes adicionados');
        }

        return redirect()->route('eventos.index')->with('success', 'Evento cadastrado com sucesso!');
    }


    public function edit(Evento $evento)
    {
        $usuarios = User::whereIn('tipo_usuario', ['voluntario_adm', 'voluntario_ext'])
            ->orderBy('name')
            ->get();
        return view('eventos.form', compact('evento', 'usuarios'));
    }

    public function show(Evento $evento)
    {
        return view('eventos.show', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'titulo' => 'required|string',
            'data_hora' => 'required|date',
            'local' => 'required|string',
            'valor_custo' => 'required|numeric',
            'valor_arrecadado' => 'nullable|numeric',
            'recorrente' => 'boolean',
            'descricao' => 'nullable|string',
            'participantes' => 'nullable|array',
        ]);

        // Atualiza campos exceto participantes
        $evento->update($request->except('participantes'));
        $evento->participantes_nomes = $evento->participantes->pluck('name')->toArray();

        $evento->skipLog = true; // Desliga o log automático temporariamente

        if ($request->has('participantes')) {
            $anteriores = $evento->participantes()->pluck('name')->toArray();
            $atuais = User::whereIn('id', $request->participantes)->pluck('name')->toArray();
            $evento->participantes()->sync($request->participantes);
            if ($anteriores != $atuais) {

                activity('Evento')
                    ->performedOn($evento)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old' => ['participantes' => $anteriores],
                        'attributes' => ['participantes' => $atuais],
                    ])
                    ->log('Participantes atualizados');
            }
        }

        return redirect()->route('eventos.index')->with('success', 'Evento atualizado com sucesso!');
    }



    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento excluído com sucesso!');
    }

    public function gerarRelatorioPDF(Request $request)
    {
        $query = Evento::query();

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        if ($request->filled('local')) {
            $query->where('local', 'like', '%' . $request->local . '%');
        }
        if ($request->filled('recorrente')) {
            $query->where('recorrente', $request->recorrente);
        }
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_hora', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data_hora', '<=', $request->data_fim);
        }

        $eventos = $query->get();

        $pdf = Pdf::loadView('eventos.relatorio-pdf', compact('eventos'));

        //return $pdf->download('relatorio_eventos.pdf');
        return $pdf->stream('relatorio_eventos.pdf');
    }
}
