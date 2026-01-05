<x-app-layout>

    <x-page-header 
        title="Gerenciamento de Cestas Básicas"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Cestas Básicas']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            {{-- Botão Novo --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                    <a href="{{ route('cestas.create') }}" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        + Nova Cesta
                    </a>
                <a href="{{ route('cestas.relatorio.pdf', request()->query()) }}" 
                target="_blank"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                📄 Gerar PDF
			    </a>
            </div>
            {{-- Filtros --}}
		    <form method="GET" class="flex gap-2 flex justify-between ">
		    <div class="flex gap-4">
                <div>
                    <label>Tipo de Entrada</label>
                    <select name="entrada_tipo" class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="doacao" {{ request('entrada_tipo') == 'doacao' ? 'selected' : '' }}>Doação</option>
                        <option value="compra" {{ request('entrada_tipo') == 'compra' ? 'selected' : '' }}>Compra</option>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status" class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="disponivel" {{ request('status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                        <option value="entregue" {{ request('status') == 'entregue' ? 'selected' : '' }}>Entregue</option>
                    </select>
                </div>
                <div>
                    <label>Origem</label>
                    <select name="origem" class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 w-48 truncate">
                        <option value="">Todos</option>
                        @foreach($origens as $user)
                            <option value="{{ $user->id }}" {{ request('origem') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Destinatario</label>
                    <select name="destinatario" class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 w-48 truncate">
                        <option value="">Todos</option>
                        @foreach($destinatarios as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>
            <div class="flex gap-4">
                <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                Filtrar
                </x-primary-button>
                <x-secondary-button onclick="window.location='{{ route('cestas.index') }}'">
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
                        <th class="px-4 py-2">Ord.   <span class="text-xs text-gray-500 select-none">(#id)</span></th>
                        <th class="px-4 py-2">Data Recebimento</th>
                        <th class="px-4 py-2">Tipo</th>
                        <th class="px-4 py-2">Origem</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Destinatário</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cestas as $cesta)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                        
                         <td class="px-4 py-2 flex items-center gap-2">
                            <span class="font-semibold text-gray-800"> {{ ($cestas->currentPage() - 1) * $cestas->perPage() + $loop->iteration }} 
                                </span>

                            <span class="text-xs text-gray-500 select-none">#{{ $cesta->id }}</span>
                        </td>

                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($cesta->data_recebimento)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 capitalize">{{ $cesta->entrada_tipo }}</td>
                        <td class="px-4 py-2">{{ $cesta->origemPessoa->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full 
                                {{ $cesta->status == 'disponivel' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($cesta->status) }}
                            </span>
				    @if($cesta->status == 'disponivel')
					<a href="{{ route('cestas.entregar.form', $cesta) }}" 
						class="text-green-600 hover:text-green-800" 
						title="Entregar">
						<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
							d="M9 17v-6h13m-5 5l5-5-5-5"/>
						</svg>
					</a>
					@endif

                        </td>
                        <td class="px-4 py-2">{{ $cesta->destinatario->name ?? '-' }}</td>

                        <td class="px-4 py-2 flex space-x-2">
					
                            <a href="{{ route('cestas.show', $cesta) }}" class="text-green-600 hover:text-green-800" title="Detalhes">
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
								d="M4 4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
								d="M14 2v6h6"/>
							</svg>
                            </a>
                            <a href="{{ route('cestas.edit', $cesta) }}" class="text-blue-600 hover:text-blue-800" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z" />
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('cestas.destroy', $cesta) }}" method="POST"
                                  onsubmit="return confirm('Deseja excluir esta cesta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                                    </svg>
                                </button>
                            </form>
                            @endif

				            @if($cesta->status == 'disponivel')
                            <a href="{{ route('cestas.entregar.form', $cesta) }}" 
                                class="text-green-600 hover:text-green-800" 
                                title="Entregar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 17v-6h13m-5 5l5-5-5-5"/>
                                </svg>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
	</div>

            {{-- Paginação --}}
            <div class="mt-4">
                {{ $cestas->withQueryString()->links('vendor.pagination.tailwind-light') }}
            </div>
        </div>
    </div>
</x-app-layout>
