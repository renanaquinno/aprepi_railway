<x-app-layout>

    <x-page-header 
        title="Gerenciamento de Eventos"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Eventos']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            {{-- Botão de criar novo --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                <a href="{{ route('eventos.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Novo Evento
                </a>
                <a href="{{ route('eventos.relatorio.pdf', request()->query()) }}" 
                target="_blank"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📄 Gerar PDF
                </a>
        </div>
            {{-- Filtros --}}
                <form method="GET" class="flex gap-2 flex justify-between ">
                    <div class="flex gap-4">

                        <input type="text" name="titulo" 
                            placeholder="Título" 
                            value="{{ request('titulo') }}" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        
                        <div>                                       
                            <label>Data Evento</label>
                            <input type="date" name="data" 
                            
                                value="{{ request('data_hora') }}" 
                                class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <input type="text" name="local" 
                            placeholder="Local" 
                            value="{{ request('local') }}" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <select name="recorrente" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Recorrente?</option>
                            <option value="1" {{ request('recorrente') == '1' ? 'selected' : '' }}>Sim</option>
                            <option value="0" {{ request('recorrente') == '0' ? 'selected' : '' }}>Não</option>
                        </select>
                    </div>
                
                    <div class="flex gap-4">
                        <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                            Filtrar 
                        </x-primary-button>
                        <x-secondary-button onclick="window.location='{{ route('eventos.index') }}'">
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
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Local</th>
                            <th class="px-4 py-2">Custo</th>
                            <th class="px-4 py-2">Arrecadação</th>
                            <th class="px-4 py-2">Recorrente</th>
                            <th class="px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eventos as $evento)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                                <td class="px-4 py-2"> {{ ($eventos->currentPage() - 1) * $eventos->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-2">{{ $evento->titulo }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($evento->data_hora)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $evento->local }}</td>
                                <td class="px-4 py-2">R$ {{ number_format($evento->valor_custo, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">R$ {{ number_format($evento->valor_arrecadado, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    {{ $evento->recorrente ? 'Sim' : 'Não' }}
                                </td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('eventos.show', $evento) }}" 
						class="text-green-600 hover:text-green-800" 
						title="Detalhes">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
								d="M4 4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
								d="M14 2v6h6"/>
							</svg>
						</a>
                                    <a href="{{ route('eventos.edit', $evento) }}" 
							class="text-blue-600 hover:text-blue-800" 
							title="Editar">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
									d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z"/>
							</svg>
						</a>
                        @if(auth()->user()->isAdmin())
                            <form action="{{ route('eventos.destroy', $evento) }}" method="POST" 
                            onsubmit="return confirm('Deseja excluir este evento?')">
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
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                                    Nenhum evento encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
		<div class="mt-4">
			{{$eventos->links('vendor.pagination.tailwind-light') }}
		</div>
        </div>
    </div>
</x-app-layout>
