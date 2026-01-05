<?php

namespace App\Http\Controllers;

use App\Models\Estadia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EstadiaController extends Controller
{
    // Lista de estadias
    public function index(Request $request)
    {
        $estadias = Estadia::with('usuario')
            ->when($request->usuario, function ($query, $value) {
                $query->whereHas('usuario', fn($q) => $q->where('name', 'like', "%$value%"));
            })
            ->when($request->data_inicio, fn($q) => $q->whereDate('data_inicio', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('data_fim', '<=', $request->data_fim))
            ->orderByDesc('data_inicio')
            ->paginate(10);

        return view('estadias.index', compact('estadias'));
    }

    // Formulário de criação
    public function create()
    {
        $usuarios = User::where('tipo_usuario', 'socio')
            ->orderBy('name')
            ->get();
        return view('estadias.form', compact('usuarios'));
    }

    // Salvar nova estadia
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        Estadia::create([
            'user_id' => $request->user_id,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('estadias.index')->with('success', 'Estadia cadastrada com sucesso.');
    }

    // Formulário de edição
    public function edit(Estadia $estadia)
    {
        $usuarios = User::orderBy('name')->get();
        return view('estadias.form', compact('estadia', 'usuarios'));
    }

    // Atualizar estadia existente
    public function update(Request $request, Estadia $estadia)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $estadia->update([
            'user_id' => $request->user_id,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('estadias.index')->with('success', 'Estadia atualizada com sucesso.');
    }

    // Excluir estadia
    public function destroy(Estadia $estadia)
    {

        $estadia->delete();

        return redirect()->route('estadias.index')->with('success', 'Estadia excluída com sucesso.');
    }


    public function relatorioPdf(Request $request)
    {
        $estadias = Estadia::with('usuario')
            ->when($request->usuario, function ($query, $value) {
                $query->whereHas('usuario', fn($q) => $q->where('name', 'like', "%$value%"));
            })
            ->when($request->data_inicio, fn($q) => $q->whereDate('data_inicio', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('data_fim', '<=', $request->data_fim))
            ->orderByDesc('data_inicio')
            ->get();

        $pdf = PDF::loadView('estadias.relatorio-pdf', compact('estadias'));
        return $pdf->stream('relatorio_estadias.pdf');

        //return $pdf->download('relatorio_doacoes.pdf');

    }
}
