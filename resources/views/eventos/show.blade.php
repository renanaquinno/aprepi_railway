<x-app-layout>
    <x-page-header 
        title="Detalhes do Evento"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Eventos', 'url' => route('eventos.index')],
            ['label' => 'Detalhes']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            <h2 class="text-2xl font-semibold mb-4">{{ $evento->titulo }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Data e Hora:</strong> {{ \Carbon\Carbon::parse($evento->data_hora)->format('d/m/Y H:i') }}</p>
                    <p><strong>Local:</strong> {{ $evento->local }}</p>
                    <p><strong>Recorrente:</strong> {{ $evento->recorrente ? 'Sim' : 'Não' }}</p>
                </div>
                <div>
                    <p><strong>Valor de Custo:</strong> R$ {{ number_format($evento->valor_custo, 2, ',', '.') }}</p>
                    <p><strong>Valor Arrecadado:</strong> R$ {{ number_format($evento->valor_arrecadado, 2, ',', '.') }}</p>
                    <p><strong>Criado em:</strong> {{ $evento->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="mt-6">
                <h3 class="text-lg font-semibold">Participantes</h3>
                <ul class="list-disc list-inside mt-2 text-gray-700">
                    @forelse($evento->participantes as $participante)
                        <li>{{ $participante->name }}</li>
                    @empty
                        <li>Nenhum participante cadastrado.</li>
                    @endforelse
                </ul>
            </div>


            <div class="mt-6">
                <h3 class="text-lg font-semibold">Descrição</h3>
                <p class="mt-2 text-gray-700">
                    {{ $evento->descricao ?? 'Sem descrição fornecida.' }}
                </p>
            </div>

		    <div class="mt-6 flex justify-end gap-4">
                {{-- Botão Voltar --}}
                <x-secondary-button onclick="window.location='{{ route('eventos.index') }}'">
                Voltar
                </x-secondary-button>

                {{-- Botão Editar --}}
                <x-primary-button onclick="window.location='{{ route('eventos.edit', $evento) }}'" class="bg-blue-800 hover:bg-blue-900">
                Editar
                </x-primary-button>

                {{-- Botão Excluir --}}
                @if(auth()->user()->isAdmin())
                <form action="{{ route('eventos.destroy', $evento) }}" method="POST"
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
    <x-log-list :logs="$evento->activities" />

</x-app-layout>
