<x-app-layout>
    <x-page-header 
        title="Pedidos de Voluntariado"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Voluntários']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nome</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Telefone</th>
                        <th class="px-4 py-2">Histórico</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voluntarios as $voluntario)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                            <td class="px-4 py-2">{{ $voluntario->id }}</td>
                            <td class="px-4 py-2">{{ $voluntario->name }}</td>
                            <td class="px-4 py-2">{{ $voluntario->email }}</td>
                            <td class="px-4 py-2">{{ $voluntario->telefone }}</td>
                            <td class="px-4 py-2">
                                @if($voluntario->aprovado_em)
                                    <span class="text-green-700">Aprovado em {{ \Carbon\Carbon::parse($voluntario->aprovado_em)->format('d/m/Y H:i') }}</span>
                                @elseif($voluntario->recusado_em)
                                    <span class="text-red-700">Recusado em {{ \Carbon\Carbon::parse($voluntario->recusado_em)->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-gray-500">Sem histórico</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($voluntario->aprovado_em)
                                    <button class="flex items-center gap-1 px-3 py-1 bg-green-600 text-white rounded shadow" readonly>
                                        <i class="fas fa-check"></i> Aprovado
                                    </button>
                                @elseif($voluntario->recusado_em)
                                    <button class="flex items-center gap-1 px-3 py-1 bg-red-600 text-white rounded shadow" readonly>
                                        <i class="fas fa-times"></i> Recusado
                                    </button>
                                @else
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.voluntarios.aprovar', $voluntario->id) }}">
                                            @csrf
                                            <button class="flex items-center gap-1 px-3 py-1 bg-green-800 hover:bg-green-700 text-white rounded shadow">
                                                <i class="fas fa-check"></i> Aprovar
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.voluntarios.recusar', $voluntario->id) }}" onsubmit="return confirm('Recusar voluntário?')">
                                            @csrf
                                            <button class="flex items-center gap-1 px-3 py-1 bg-red-800 hover:bg-red-700 text-white rounded shadow">
                                                <i class="fas fa-times"></i> Recusar
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Nenhum pedido de voluntariado pendente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

          {{-- Paginação --}}
            <div class="mt-4">
                {{ $voluntarios->withQueryString()->links('vendor.pagination.tailwind-light') }}
            </div>
    </div>
</x-app-layout>
