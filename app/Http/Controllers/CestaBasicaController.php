<?php

namespace App\Http\Controllers;

use App\Models\CestaBasica;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CestaBasicaController extends Controller
{
    //  Listar Cestas
    public function index(Request $request)
    {
        $query = CestaBasica::with(['origemPessoa', 'destinatario']);

        // Filtros
        if ($request->filled('entrada_tipo')) {
            $query->where('entrada_tipo', $request->entrada_tipo);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_recebimento', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_recebimento', '<=', $request->data_fim);
        }

        if ($request->filled('origem')) {
            $query->where('origem', $request->origem);
        }

        if ($request->filled('destinatario')) {
            $query->where('user_id', $request->destinatario);
        }

        $cestas = $query->orderBy('created_at', 'desc')->paginate(10);
        $destinatarios = User::where('tipo_usuario', 'socio')
            ->orderBy('name')
            ->get();

        // LISTA PARA O SELECT DE ORIGEM (todos)
        $origens = User::orderBy('name')->get();

        return view('cestas.index', compact('cestas', 'destinatarios', 'origens'));
    }

    public function show(CestaBasica $cesta)
    {
        $logs = $cesta->activities;

        //dd($logs); // agora deve trazer as atividades

        return view('cestas.show', compact('cesta'));
    }
    //  Formulário de criação
    public function create()
    {
        $usuarios = User::orderBy('name')->get();
        return view('cestas.form', compact('usuarios'));
    }

    //  Salvar nova cesta
    public function store(Request $request)
    {
        $request->validate([
            'data_recebimento' => 'required|date',
            'entrada_tipo'     => 'required|in:doacao,compra',
            'origem'           => 'nullable|exists:users,id',
            'status'           => 'required|in:disponivel',
            'observacoes'      => 'nullable|string',
        ]);

        CestaBasica::create($request->all());

        return redirect()->route('cestas.index')->with('success', 'Cesta cadastrada com sucesso!');
    }

    //  Formulário de edição
    public function edit(CestaBasica $cesta)
    {
        $usuarios = User::orderBy('name')->get();

        return view('cestas.form', compact('cesta', 'usuarios'));
    }

    //  Atualizar cesta
    public function update(Request $request, CestaBasica $cesta)
    {

        $request->validate([
            'data_recebimento' => 'required|date',
            'entrada_tipo'     => 'required|in:doacao,compra',
            'origem'           => 'nullable|exists:users,id',
            'status'           => 'required|in:disponivel,entregue',
            'observacoes'      => 'nullable|string',
        ]);

        $cesta->update($request->all());

        return redirect()->route('cestas.index')->with('success', 'Cesta atualizada com sucesso!');
    }

    //  Excluir cesta
    public function destroy(CestaBasica $cesta)
    {
        $cesta->delete();
        return redirect()->route('cestas.index')->with('success', 'Cesta excluída com sucesso!');
    }

    //  Entregar Cesta (formulário)
    public function entregar(CestaBasica $cesta)
    {
        // Verifica se a cesta está disponível
        if ($cesta->status !== 'disponivel') {
            return redirect()->route('cestas.index')->with('error', 'Esta cesta já foi entregue.');
        }

        $usuarios = User::where('tipo_usuario', 'socio')
            ->orderBy('name')
            ->get();


        return view('cestas.entregar', compact('cesta', 'usuarios'));
    }

    //  Realizar entrega (salvar)
    public function salvarEntrega(Request $request, CestaBasica $cesta)
    {
        if ($cesta->status !== 'disponivel') {
            return redirect()->route('cestas.index')->with('error', 'Esta cesta já foi entregue.');
        }

        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'data_entrega' => 'required|date',
        ]);

        $cesta->update([
            'status'       => 'entregue',
            'user_id'      => $request->user_id,
            'data_entrega' => $request->data_entrega,
        ]);

        return redirect()->route('cestas.index')->with('success', 'Cesta entregue com sucesso!');
    }

    public function gerarRelatorioPDF(Request $request)
    {
        $query = CestaBasica::with('destinatario', 'origemPessoa');

        if ($request->filled('entrada_tipo')) {
            $query->where('entrada_tipo', $request->entrada_tipo);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_recebimento', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data_recebimento', '<=', $request->data_fim);
        }
        if ($request->filled('destinatario')) {
            $query->where('user_id', $request->destinatario);
        }

        if ($request->filled('origemPessoa')) {
            $query->where('origem', $request->origem);
        }

        $cestas = $query->get();

        $pdf = Pdf::loadView('cestas.relatorio-pdf', compact('cestas'));

        //return $pdf->download('relatorio_cestas.pdf');
        return $pdf->stream('relatorio_cestas.pdf');
    }
}
