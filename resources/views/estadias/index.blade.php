<x-app-layout>
    <x-page-header 
        title="Gerenciamento de Estadias"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Estadias']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            {{-- Botão + Filtros --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                <a href="{{ route('estadias.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nova Estadia
                </a>

                <a href="{{ route('estadias.relatorio.pdf', request()->query()) }}" 
                    target="_blank"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📄 Gerar PDF
                </a>
            </div>

            {{-- Filtros --}}
            <form method="GET" class="flex gap-2 flex justify-between ">
                <div class="flex gap-4">
                    <div>
                        <input type="text" name="usuario" 
                            value="{{ request('usuario') }}" 
                            placeholder="Nome do usuário"
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label>Data Início</label>
                        <input type="date" name="data_inicio" 
                            value="{{ request('data_inicio') }}" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label>Data Fim</label>
                        <input type="date" name="data_fim" 
                            value="{{ request('data_fim') }}" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex gap-4">
                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        Filtrar 
                    </x-primary-button>
                    <x-secondary-button onclick="window.location='{{ route('estadias.index') }}'">
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
                        <th class="px-4 py-2 text-left">Ord.</th>
                        <th class="px-4 py-2 text-left">Usuário</th>
                        <th class="px-4 py-2 text-left">Início</th>
                        <th class="px-4 py-2 text-left">Fim</th>
                        <th class="px-4 py-2 text-left">Observações</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estadias as $estadia)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                            <td class="px-4 py-2">{{ ($estadias->currentPage() - 1) * $estadias->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $estadia->usuario->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($estadia->data_inicio)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">
                                {{ $estadia->data_fim ? \Carbon\Carbon::parse($estadia->data_fim)->format('d/m/Y') : '---' }}
                            </td>
                            <td class="px-4 py-2">{{ $estadia->observacoes ?? '-' }}</td>
                            
                            <td class="px-4 py-2 flex space-x-2">
                                

                                {{-- Editar (Pincel) --}}
                                <a href="{{ route('estadias.edit', $estadia) }}" 
                                class="text-blue-600 hover:text-blue-800" 
                                title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z"/>
                                    </svg>
                                </a>

                                {{-- Excluir (Lixeira) --}}
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('estadias.destroy', $estadia) }}" method="POST" 
                                        onsubmit="return confirm('Deseja excluir esta estadia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                Nenhuma estadia encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="mt-4">
            {{$estadias->links('vendor.pagination.tailwind-light') }}
        </div>
    </div>
</x-app-layout>
