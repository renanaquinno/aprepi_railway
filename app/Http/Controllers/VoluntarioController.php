<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VoluntarioController extends Controller
{
    public function create()
    {
        return view('voluntariado.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|string|max:14|unique:users,cpf',
            'telefone' => 'required|string',
            'data_nascimento' => 'required|date',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'password' => Hash::make($request->password),
            'tipo_usuario' => 'voluntario_ext',
            'ativo' => 0, // inicia como inativo até aprovação
        ]);

        return redirect()->route('voluntariado.create')->with('success', 'Solicitação enviada com sucesso! Entraremos em contato.');
    }
}
