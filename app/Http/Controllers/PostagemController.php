<?php

namespace App\Http\Controllers;

use App\Models\Postagem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostagemController extends Controller
{
    public function publicIndex()
    {
        $postagens = Postagem::whereNotNull('publicado_em')
            ->orderByDesc('publicado_em')
            ->paginate(10);

            return view('postagens.index', compact('postagens'));
    }

    public function index(Request $request)
    {
        $query = Postagem::latest();

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%'.$request->titulo.'%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $postagens = $query->paginate(10);
        return view('postagens.admin.index', compact('postagens'));
    }


    public function create()
    {
        $categorias = ['Notícia', 'Aviso', 'Artigo', 'Informativo'];
        return view('postagens.form', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria' => 'required|string|max:100',
            'status' => 'required|in:rascunho,publicado',
        ]);


        if ($data['status'] === 'publicado') {
            $data['publicado_em'] = now();
        } else {
            $data['publicado_em'] = null;
        }

        $data['user_id'] = Auth::id();

        $baseSlug = Str::slug($data['titulo']);
        $slug = $baseSlug;
        $contador = 1;

        while (Postagem::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $contador;
            $contador++;
        }

        $data['slug'] = $slug;

        $postagem = Postagem::create($data);

        return redirect()->route('postagens.show', $postagem)->with('success', 'Postagem criada com sucesso.');
    }

    public function show(Postagem $postagem)
    { 
        $postagem->load(['user', 'comentarios.user']);
        return view('postagens.show', compact('postagem'));
    }

    public function edit(Postagem $postagem)
    {
        $categorias = ['Notícia', 'Aviso', 'Artigo', 'Informativo'];
        return view('postagens.form', compact('postagem', 'categorias'));
    }

    public function update(Request $request, Postagem $postagem)
    {

        $data = $request->validate([
           'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria' => 'required|string|max:100',
            'status' => 'required|in:rascunho,publicado',
        ]);

        if ($postagem->titulo !== $data['titulo']) {
            $baseSlug = Str::slug($data['titulo']);
            $slug = $baseSlug;
            $contador = 1;

            while (Postagem::where('slug', $slug)->where('id', '!=', $postagem->id)->exists()) {
                $slug = $baseSlug . '-' . $contador;
                $contador++;
            }

            $data['slug'] = $slug;
        }


        $postagem->update($data);

        return redirect()->route('postagens.admin.index', $postagem)->with('success', 'Postagem atualizado com sucesso.');
    }

   
    public function uploadImagem(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Nenhum arquivo recebido'], 400);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Arquivo inválido'], 400);
        }

        $folder = 'postagens/' . date('Y') . '/' . date('m');
        $path = $file->store($folder, 'public');

        return response()->json([
            'location'     => asset('storage/' . $path),
        ]);
    }


    public function destroy(Postagem $postagem)
    {
        $postagem->delete();
        return redirect()->route('postagens.admin.index')->with('success', 'Postagem deletado com sucesso.');
    }
}
