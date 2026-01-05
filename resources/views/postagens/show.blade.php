<x-app-layout>
    <x-page-header title="Detalhes da Postagem" :breadcrumbs="[['label' => 'Postagens', 'url' => route('postagens.index')], ['label' => $postagem->titulo]]" />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            {{-- Título --}}
            <h1 class="text-3xl font-bold mb-2">{{ $postagem->titulo }}</h1>

            {{-- Informações da postagem --}}
            <div class="mb-6 flex flex-wrap gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-1 bg-gray-100 px-3 py-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" />
                    </svg>
                    <span>Publicado:
                        {{ $postagem->publicado_em ? $postagem->publicado_em->format('d/m/Y H:i') : $postagem->created_at->format('d/m/Y H:i') }}</span>

                </div>
                <div class="flex items-center gap-1 bg-gray-100 px-3 py-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" />
                    </svg>
                    @if ($postagem->updated_at && $postagem->updated_at != $postagem->created_at)
                        <span>Atualizado: {{ $postagem->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v4a1 1 0 001 1h16a1 1 0 001-1V7M5 7v4m14-4v4M5 7h14" />
                    </svg>
                    <span>Categoria: {{ $postagem->categoria ?? 'Sem categoria' }}</span>
                </div>

                <div class="flex items-center gap-1 bg-green-100 text-green-800 px-3 py-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A2 2 0 007 19h10a2 2 0 001.879-1.196l3-6A2 2 0 0020 9H4a2 2 0 00-1.879 2.804l3 6z" />
                    </svg>
                    <span>Autor: {{ $postagem->user?->name ?? 'Não informado' }}</span>
                </div>

                <div
                    class="flex items-center gap-1 px-3 py-1 rounded-full 
                            {{ $postagem->status === 'publicado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Status: {{ ucfirst($postagem->status) }}</span>
                </div>
            </div>

            {{-- Imagem --}}
            @if ($postagem->imagem)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $postagem->imagem) }}" alt="Imagem da postagem"
                        class="w-full max-h-80 object-cover rounded">
                </div>
            @endif

            {{-- Conteúdo --}}
            <div class="prose max-w-none">
                {!! $postagem->conteudo !!}
            </div>

            <section class="mt-8 pt-6 border-t border-gray-300">
                <h3 class="text-2xl font-semibold mb-6">Comentários</h3>

                <div class="space-y-4">
                    @foreach ($postagem->comentarios as $comentario)
                        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span
                                    class="font-medium text-gray-800">{{ $comentario->user->name ?? 'Anônimo' }}</span>
                                <small
                                    class="text-gray-500 text-sm">{{ $comentario->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-gray-700">{{ $comentario->conteudo }}</p>
                        </div>
                    @endforeach
                </div>

                @if (auth()->check())
                    <form action="{{ route('comentarios.store', $postagem->slug) }}" method="POST" class="mt-6">
                        @csrf
                        <textarea name="conteudo"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2"
                            placeholder="Escreva um comentário" rows="3"></textarea>
                        <button type="submit"
                            class="bg-blue-800 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-lg transition-colors">
                            Comentar
                        </button>
                    </form>
                @else
                    <p class="mt-4 text-gray-600">
                        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Faça login</a> para
                        comentar.
                    </p>
                @endif

            </section>
            {{-- Botões --}}
            @php
                $url =
                    auth()->user()?->id === $postagem->user_id
                        ? route('postagens.admin.index')
                        : route('postagens.index');
            @endphp

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button onclick="window.location='{{ $url }}'">
                    Voltar
                </x-secondary-button>

                @if (auth()->user()?->id === $postagem->user_id)
                    <x-primary-button onclick="window.location='{{ route('postagens.edit', $postagem->slug) }}'"
                        class="bg-blue-800 hover:bg-blue-900">
                        Editar
                    </x-primary-button>

                    <form action="{{ route('postagens.destroy', $postagem->slug) }}" method="POST"
                        onsubmit="return confirm('Deseja realmente excluir esta postagem?')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>
                            Excluir
                        </x-danger-button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Histórico de logs (se você estiver usando spatie/laravel-activitylog) --}}
    @if (isset($postagem->activities))
        <x-log-list :logs="$postagem->activities" />
    @endif
</x-app-layout>
