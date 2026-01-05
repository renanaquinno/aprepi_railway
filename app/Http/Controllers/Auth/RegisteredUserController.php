<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);
        $request->validate([
        'name' => 'required|string|max:255',
        'data_nascimento' => 'required|date',
        'telefone' => 'required|string|max:20',
        'email' => 'required|string|email|max:255|unique:users,email',
        'cpf' => 'required|string|size:11|unique:users,cpf',
        'password' => 'required|string|confirmed|min:8',
        'tipo_usuario' => 'required|in:admin,voluntario_adm,voluntario_ext,socio,doador',
        ]);

        $user = User::create([
        'name' => $request->name,
        'data_nascimento' => $request->data_nascimento,
        'telefone' => $request->telefone,
        'email' => $request->email,
        'cpf' => $request->cpf,
        'password' => Hash::make($request->password),
        'tipo_usuario' => $request->tipo_usuario,
        'endereco' => $request->endereco,
        'cidade' => $request->cidade,
        'estado' => $request->estado,
        'cep' => $request->cep,
        'observacoes' => $request->observacoes,
        'ativo' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
