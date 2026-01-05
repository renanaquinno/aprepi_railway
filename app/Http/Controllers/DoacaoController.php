<?php

namespace App\Http\Controllers;

use App\Models\Doacao;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\MercadoPagoService;

class DoacaoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Doacao::query()->with('user');
        // Filtra para usuário comum: só vê suas próprias doações
        if (!($user->isAdmin() || $user->isVoluntarioAdm())) {
            $query->where('user_id', $user->id);
        }

        // Filtros adicionais
        if ($request->filled('data_inicio')) {
            $query->where('data_doacao', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('data_doacao', '<=', $request->data_fim);
        }
        // Só admin/voluntario_adm pode filtrar por status ou usuário
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id') && ($user->isAdmin() || $user->isVoluntarioAdm())) {
            $query->where('user_id', $request->user_id);
        }
        // Clona antes da paginação para total
        $totalQuery = (clone $query);
        $total = $totalQuery->sum('valor');

        // Paginação preservando filtros
        $doacoes = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $usuarios = User::all();

        return view('doacoes.index', compact('doacoes', 'total', 'usuarios'));
    }


    public function create()
    {
        $usuarios = User::all();
        return view('doacoes.form', compact('usuarios'));
    }

    public function edit(Doacao $doacao)
    {
        $user = auth()->user();
        // Somente admin ou voluntario_adm podem editar
        if (!($user->isAdmin() || $user->isVoluntarioAdm())) {
            abort(403, 'Você não tem permissão para editar esta doação.');
        }
        $usuarios = User::all();
        return view('doacoes.form', compact('doacao', 'usuarios'));
    }

    public function show(Doacao $doacao)
    {
        return view('doacoes.show', compact('doacao'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_doacao' => 'required|date',
            'valor' => 'required|numeric|min:0.01',
            'forma_pagamento' => 'required|string|in:boleto,cartao,pix,transferencia,especie',
            'observacoes' => 'nullable|string',
        ]);

        // Cria a doação como pendente
        $doacao = Doacao::create([
            ...$validated,
            'status' => 'pendente',
        ]);

        // Se o usuário clicou em "Cadastrar e Pagar" **e** a forma de pagamento é boleto ou cartão
        if ($request->acao === 'cadastrar_pagar' && in_array($doacao->forma_pagamento, ['boleto', 'cartao'])) {
            try {
                $mp = new \App\Services\MercadoPagoService();
                $url = $mp->criarPreferencia($doacao); // retorna link do checkout
                return redirect($url);
            } catch (\Exception $e) {
                return redirect()->route('doacoes.index')
                    ->with('error', 'Erro ao iniciar pagamento: ' . $e->getMessage());
            }
        }

        // Para pix, transferência ou espécie, apenas cadastra a doação normalmente
        $mensagem = match ($doacao->forma_pagamento) {
            'pix' => 'Doação cadastrada! Utilize a chave PIX ou QR code para efetuar o pagamento.',
            'transferencia' => 'Doação cadastrada! Use os dados bancários fornecidos para transferência.',
            'especie' => 'Doação cadastrada! Compareça à associação para entregar a doação em espécie.',
            default => 'Doação cadastrada com sucesso!',
        };

        return redirect()->route('doacoes.index')->with('success', $mensagem);
    }


    public function pagarDoacao($doacaoId, MercadoPagoService $mpService)
    {
        $doacao = Doacao::findOrFail($doacaoId);

        // Cria a preferência e obtém o ID
        $preferenceId = $mpService->criarPreferencia($doacao);

        // Redireciona para o checkout do Mercado Pago
        return redirect("https://www.mercadopago.com.br/checkout/v1/redirect?pref_id={$preferenceId}");
    }


    public function update(Request $request, Doacao $doacao)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_doacao' => 'required|date',
            'valor' => 'required|numeric',
            'forma_pagamento' => 'required',
            'observacoes' => 'nullable',
            'status' => 'required|in:realizada,pendente,cancelada',
        ]);

        $doacao->update($request->only([
            'user_id',
            'data_doacao',
            'valor',
            'forma_pagamento',
            'observacoes',
            'status',
        ]));

        return redirect()->route('doacoes.index')->with('success', 'Doação atualizada com sucesso!');
    }

    public function destroy(Doacao $doacao)
    {
        $doacao->delete();
        return redirect()->route('doacoes.index')->with('success', 'Doação excluída com sucesso!');
    }


    public function gerarRelatorioPDF(Request $request)
    {
        $user = $request->user(); // usuário autenticado

        // Consulta com filtros
        $query = Doacao::with('user');

        // Filtros de data e status
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_doacao', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data_doacao', '<=', $request->data_fim);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // Filtra pelo usuário apenas se não for admin ou voluntario_adm
        if (!($user->isAdmin() || $user->isVoluntarioAdm())) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            // Apenas admin/voluntario_adm pode filtrar por user_id
            $query->where('user_id', $request->user_id);
        }
        $doacoes = $query->get();
        $total = $doacoes->sum('valor');
        // Gera PDF com base na view
        $pdf = Pdf::loadView('doacoes.relatorio-pdf', compact('doacoes', 'total'));
        //return $pdf->download('relatorio_doacoes.pdf');
        return $pdf->stream('relatorio_doacoes.pdf');
    }

    public function success(Request $request)
    {
        // ID da preferência ou pagamento vem nos parâmetros da URL
        $paymentId = $request->query('payment_id');
        $externalReference = $request->query('external_reference'); // ID da doação que você enviou

        // Aqui você pode atualizar a doação no banco de dados
        $doacao = \App\Models\Doacao::find($externalReference);
        if ($doacao) {
            $doacao->status = 'realizada';
            $doacao->save();
        }

        return redirect()->route('doacoes.index')->with('success', 'Pagamento aprovado com sucesso!');
    }

    public function failure(Request $request)
    {
        return redirect()->route('doacoes.index')->with('error', 'O pagamento não foi concluído.');
    }

    public function pending(Request $request)
    {
        return redirect()->route('doacoes.index')->with('info', 'Pagamento pendente. Assim que for confirmado, atualizaremos seu status.');
    }


    public function webhook(Request $request)
    {
        \Log::info('Webhook Mercado Pago recebido:', $request->all());

        $type = $request->input('type');
        $paymentId = $request->input('data.id');

        if ($type === 'payment' && $paymentId) {
            $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN');

            // Consultar detalhes do pagamento
            $ch = curl_init("https://api.mercadopago.com/v1/payments/{$paymentId}");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$accessToken}"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if ($response && isset($response['external_reference'])) {
                $doacaoId = $response['external_reference'];
                $status = $response['status']; // approved | pending | rejected | etc

                $doacao = \App\Models\Doacao::find($doacaoId);
                if ($doacao) {
                    if ($status === 'approved') {
                        $doacao->status = 'confirmada';
                    } elseif ($status === 'rejected') {
                        $doacao->status = 'rejeitada';
                    } else {
                        $doacao->status = 'pendente';
                    }
                    $doacao->save();
                }
            }
        }
        return response()->json(['status' => 'ok']);
    }
}
