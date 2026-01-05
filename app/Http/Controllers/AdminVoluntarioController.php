<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminVoluntarioController extends Controller
{
    // Listagem de pedidos de voluntário
    public function index()
    {
        $voluntarios = User::where('tipo_usuario', 'voluntario_ext')
            ->where(function($query) {
                $query->whereNotNull('recusado_em')
                    ->orWhereNotNull('aprovado_em');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.voluntarios.index', compact('voluntarios'));
    }


    // Aprovar voluntário
    public function aprovar($id)
    {
        $voluntario = User::findOrFail($id);
        $voluntario->ativo = 1;
        $voluntario->aprovado_em = now();
        $voluntario->recusado_em = null; // zera recusa caso aprovado
        $voluntario->save();

        return redirect()->route('admin.voluntarios.index')->with('success', 'Voluntário aprovado');
    }


    public function recusar($id)
    {
        $voluntario = User::findOrFail($id);
        $voluntario->ativo = 0;
        $voluntario->recusado_em = now();
        $voluntario->aprovado_em = null; // zera aprovação caso recusado
        $voluntario->save();

        return redirect()->route('admin.voluntarios.index')->with('success', 'Voluntário recusado');
    }

}
