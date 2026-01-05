<x-app-layout>
    @php
        $breadcrumbs = [['label' => 'Doações']];
        if (in_array(auth()->user()->tipo_usuario, ['admin', 'voluntario_adm'])) {
            array_unshift($breadcrumbs, ['label' => 'Dashboard', 'url' => route('dashboard')]);
        }
    @endphp

    <x-page-header title="Gerenciamento de Doações" :breadcrumbs="$breadcrumbs" />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            {{-- Botão de criar novo --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                <a href="{{ route('doacoes.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nova Doação
                </a>
                <a href="{{ route('doacoes.relatorio.pdf', request()->all()) }}" target="_blank"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📄 Gerar PDF
                </a>
            </div>
            {{-- Filtros --}}
            <form method="GET" class="flex gap-2 flex justify-between ">
                <div class="flex gap-4">
                    <div>
                        <label>Data Início</label>
                        <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label>Data Fim</label>
                        <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status"
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="realizada" {{ request('status') == 'realizada' ? 'selected' : '' }}>Realizada
                            </option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente
                            </option>
                            <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada
                            </option>
                        </select>
                    </div>
                    @if (auth()->user()->isAdmin() || auth()->user()->isVoluntarioAdm())
                        <div>
                            <label>Doador</label>
                            <select name="user_id"
                                class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 w-48 truncate">
                                <option value="">Todos</option>
                                @foreach ($usuarios as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif


                </div>

                <div class="flex gap-4">
                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        Filtrar
                    </x-primary-button>
                    <x-secondary-button onclick="window.location='{{ route('doacoes.index') }}'">
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
                        <th class="px-4 py-2">Ord. <span class="text-xs text-gray-500 select-none">(#id)</span></th>
                        <th class="px-4 py-2">Doador</th>
                        <th class="px-4 py-2">Data</th>
                        <th class="px-4 py-2">Valor</th>
                        <th class="px-4 py-2">Forma</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Observações</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doacoes as $doacao)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                            <td class="px-4 py-2 flex items-center gap-2">
                                <span class="font-semibold text-gray-800">
                                    {{ ($doacoes->currentPage() - 1) * $doacoes->perPage() + $loop->iteration }}
                                </span>

                                <span class="text-xs text-gray-500 select-none">#{{ $doacao->id }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $doacao->user->name }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2">R$ {{ number_format($doacao->valor, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ ucfirst($doacao->forma_pagamento) }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 rounded-full 
                                {{ $doacao->status == 'realizada' ? 'bg-green-100 text-green-700' : ($doacao->status == 'pendente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($doacao->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $doacao->observacoes ?? '-' }}</td>

                            <td class="px-4 py-2 flex space-x-2">
                                <a href="{{ route('doacoes.show', $doacao) }}"
                                    class="text-green-600 hover:text-green-800" title="Detalhes">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 2v6h6" />
                                    </svg>
                                </a>

                                @if (auth()->user()->isAdmin() || auth()->user()->isVoluntarioAdm())
                                    <a href="{{ route('doacoes.edit', $doacao) }}"
                                        class="text-blue-600 hover:text-blue-800" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z" />
                                        </svg>
                                    </a>
                                @endif

                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('doacoes.destroy', $doacao) }}" method="POST"
                                        onsubmit="return confirm('Deseja excluir esta doação?')">
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="mt-4">
            <!-- {{ $doacoes->withQueryString()->links() }} -->
            {{ $doacoes->links('vendor.pagination.tailwind-light') }}
        </div>

        {{-- Total --}}
        <div class="mt-6">
            <strong>Total Arrecadado (com filtros aplicados): </strong>
            <span class="text-xl">R$ {{ number_format($total, 2, ',', '.') }}</span>
        </div>
    </div>
    </div>
</x-app-layout>
