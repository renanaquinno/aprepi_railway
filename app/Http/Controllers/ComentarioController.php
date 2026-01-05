<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Postagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, Postagem $postagem)
    {
        $data = $request->validate([
            'conteudo' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comentarios,id',
        ]);

        $data['user_id'] = Auth::id();
        $data['postagem_id'] = $postagem->id;

        Comentario::create($data);

        return redirect()->route('postagens.show', $postagem)->with('success', 'Comentário enviado com sucesso.');
    }

    public function edit(Comentario $comentario)
    {
        $this->authorize('update', $comentario);
        return view('comentarios.edit', compact('comentario'));
    }

    public function update(Request $request, Comentario $comentario)
    {
        $this->authorize('update', $comentario);

        $data = $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        $comentario->update($data);

        return redirect()->route('postagens.show', $comentario->postagem_id)->with('success', 'Comentário atualizado com sucesso.');
    }

    public function destroy(Comentario $comentario)
    {

        $postId = $comentario->postagem_id;
        $comentario->delete();

        return redirect()->route('postagens.show', $postId)->with('success', 'Comentário deletado com sucesso.');
    }
}
