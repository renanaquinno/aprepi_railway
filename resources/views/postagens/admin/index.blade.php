<x-app-layout>
    <x-page-header 
        title="Gerenciamento de Postagens"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Postagens']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">

            {{-- Botão + Filtros --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                <a href="{{ route('postagens.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nova Postagem
                </a>
            </div>

            {{-- Filtros --}}
            <form method="GET" class="flex gap-2 justify-between flex-wrap">
                <div class="flex gap-4 flex-wrap">
                    <div>
                        <input type="text" name="titulo" 
                            value="{{ request('titulo') }}" 
                            placeholder="Título"
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="status" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="rascunho" {{ request('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ request('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>

                    <div>
                        <label>Categoria</label>
                        <select name="categoria" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todas</option>
                            <option value="Notícia" {{ request('categoria') == 'Notícia' ? 'selected' : '' }}>Notícia</option>
                            <option value="Aviso" {{ request('categoria') == 'Aviso' ? 'selected' : '' }}>Aviso</option>
                            <option value="Artigo" {{ request('categoria') == 'Artigo' ? 'selected' : '' }}>Artigo</option>
                            <option value="Informativo" {{ request('categoria') == 'Informativo' ? 'selected' : '' }}>Informativo</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4">
                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        Filtrar
                    </x-primary-button>
                    <x-secondary-button onclick="window.location='{{ route('postagens.admin.index') }}'">
                        Limpar
                    </x-secondary-button>
                </div>
            </form>
        </div>

        {{-- Tabela --}}
        @include('components.alert-messages')
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-slate-300">
                    <tr>
                        <th class="px-4 py-2">Ord.</th>
                        <th class="px-4 py-2">Título</th>
                        <th class="px-4 py-2">Categoria</th>
                        <th class="px-4 py-2">Data Publicação</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($postagens as $postagem)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                            <td class="px-4 py-2">{{ ($postagens->currentPage() - 1) * $postagens->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $postagem->titulo }}</td>
                            <td class="px-4 py-2">{{ $postagem->categoria ?? '-' }}</td>
                            <td class="px-4 py-2">
                                {{ $postagem->publicado_em ? $postagem->publicado_em->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="font-semibold {{ $postagem->status === 'publicado' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ ucfirst($postagem->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex space-x-2">
                                {{-- Visualizar --}}
                                <a href="{{ route('postagens.show', $postagem) }}" 
                                   class="text-green-600 hover:text-green-800" title="Visualizar">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M4 4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M14 2v6h6"/>
                                    </svg>
                                </a>

                                {{-- Editar --}}
                                <a href="{{ route('postagens.edit', $postagem) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="Editar">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z"/>
                                    </svg>
                                </a>

                                {{-- Excluir --}}
                                <form action="{{ route('postagens.destroy', $postagem) }}" method="POST" 
                                      onsubmit="return confirm('Deseja realmente excluir esta postagem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6"/>
                                            </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                Nenhuma postagem encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="mt-4">
            {{ $postagens->withQueryString()->links('vendor.pagination.tailwind-light') }}
        </div>
    </div>
</x-app-layout>