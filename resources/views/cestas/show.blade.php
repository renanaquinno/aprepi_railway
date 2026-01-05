<x-app-layout>
    <x-page-header 
        title="Detalhes da Cesta"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Cestas', 'url' => route('cestas.index')],
            ['label' => 'Detalhes']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            <h2 class="text-2xl font-semibold mb-4">Cesta #{{ $cesta->id }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Data de Recebimento:</strong> {{ \Carbon\Carbon::parse($cesta->data_recebimento)->format('d/m/Y') }}</p>
                    <p><strong>Entrada:</strong> {{ ucfirst($cesta->entrada_tipo) }}</p>
                    <p><strong>Origem:</strong> {{ $cesta->origemPessoa->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Status:</strong> {{ ucfirst($cesta->status) }}</p>
                    <p><strong>Entregue para:</strong> {{ $cesta->destinatario?->name ?? 'Não entregue' }}</p>
                    <p><strong>Data da Entrega:</strong> {{ $cesta->data_entrega ? \Carbon\Carbon::parse($cesta->data_entrega)->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold">Observações</h3>
                <p class="mt-2 text-gray-700">
                    {{ $cesta->observacoes ?? 'Sem observações.' }}
                </p>
            </div>

        
            <div class="mt-6 flex justify-end gap-4">
                {{-- Botão Voltar --}}
                <x-secondary-button onclick="window.location='{{ route('cestas.index') }}'">
                Voltar
                </x-secondary-button>

                {{-- Botão Editar --}}
                <x-primary-button onclick="window.location='{{ route('cestas.edit', $cesta) }}'" class="bg-blue-800 hover:bg-blue-900">
                Editar
                </x-primary-button>

                {{-- Botão Excluir --}}
                @if(auth()->user()->isAdmin())
                <form action="{{ route('cestas.destroy', $cesta) }}" method="POST"
                onsubmit="return confirm('Deseja realmente excluir este evento?')">
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
    <x-log-list :logs="$cesta->activities" />
</x-app-layout>
