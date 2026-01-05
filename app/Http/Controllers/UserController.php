<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->filled('tipo_usuario')) {
            $query->where('tipo_usuario', $request->input('tipo_usuario'));
        }

        $usuarios = $query->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }


    public function create()
    {
        return view('usuarios.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|unique:users,cpf',
            'password' => 'required|min:6',
            'tipo_usuario' => 'required|in:admin,voluntario_adm,voluntario_ext,socio,doador',
            ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'tipo_usuario.required' => 'Selecione o tipo de usuário.',
            'tipo_usuario.in' => 'Tipo de usuário inválido.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password),
            'tipo_usuario' => $request->tipo_usuario,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        return view('usuarios.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|unique:users,cpf,' . $user->id,
            'tipo_usuario' => 'required|in:admin,voluntario_adm,voluntario_ext,socio,doador',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'tipo_usuario.required' => 'Selecione o tipo de usuário.',
            'tipo_usuario.in' => 'Tipo de usuário inválido.',
        ]);

        // Restrição para voluntário_adm não alterar tipo_usuario
        if (auth()->user()->isVoluntarioAdm()) {
            $request->merge(['tipo_usuario' => $user->tipo_usuario]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'cep' => $request->cep,
            'observacoes' => $request->observacoes,
            'ativo' => $request->ativo,
            'tipo_usuario' => $request->tipo_usuario,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
    }

    public function show(User $user)
    {
        return view('usuarios.show', compact('user'));
    }

    public function gerarRelatorioPDF(Request $request)
    {
        $query = User::query();
        if ($request->filled('tipo_usuario')) {
            $query->where('tipo_usuario', $request->tipo_usuario);
        }
        if ($request->filled('nome')) {
            $query->where('name', 'like', '%' . $request->nome . '%');
        }

        $usuarios = $query->get();

        $pdf = Pdf::loadView('usuarios.relatorio-pdf', compact('usuarios'));

        //return $pdf->download('relatorio_usuarios.pdf');
        return $pdf->stream('relatorio_usuarios.pdf');
    }


}
