<x-app-layout>
    <x-page-header 
        title="Detalhes da Data Comemorativa"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Datas Comemorativas', 'url' => route('datas_comemorativas.index')],
            ['label' => 'Detalhes']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            <h2 class="text-2xl font-semibold mb-4">{{ $datas_comemorativa->titulo }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            	<div>
                    	<p><strong>Data Comemorativa:</strong> 
				{{ $datas_comemorativa->data ? \Carbon\Carbon::parse($datas_comemorativa->data)->format('d/m/Y') : 'Não informada' }}
				</p>

				<p><strong>Criado em:</strong> 
				{{ $datas_comemorativa->created_at ? $datas_comemorativa->created_at->format('d/m/Y H:i') : 'Não informado' }}
				</p>

				<p><strong>Último envio:</strong> 
				@php
					$ultimoEnvio = $datas_comemorativa->envios->last()?->data_envio;
				@endphp
				{{ $ultimoEnvio ? \Carbon\Carbon::parse($ultimoEnvio)->format('d/m/Y') : 'Nunca' }}
				</p>

                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold">Mensagem</h3>
                <div class="mt-2 text-gray-700">
                    {!! $datas_comemorativa->mensagem !!}
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                {{-- Botão Voltar --}}
                <x-secondary-button onclick="window.location='{{ route('datas_comemorativas.index') }}'">
                    Voltar
                </x-secondary-button>

                {{-- Botão Editar --}}
                <x-primary-button onclick="window.location='{{ route('datas_comemorativas.edit', $datas_comemorativa) }}'" class="bg-blue-800 hover:bg-blue-900">
                    Editar
                </x-primary-button>

                {{-- Botão Excluir --}}
                @if(auth()->user()->isAdmin())
                    <form action="{{ route('datas_comemorativas.destroy', $datas_comemorativa) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir esta data comemorativa?')">
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

    {{-- Histórico de envios --}}
    @if($datas_comemorativa->envios->count())
        <div class="bg-white rounded-md mb-6 p-4">
            <h3 class="text-lg font-semibold mb-2">Histórico de Envios</h3>
            <ul class="list-disc pl-5 text-gray-700">
                @foreach($datas_comemorativa->envios as $envio)
                    <li>
                        Enviado em: {{ \Carbon\Carbon::parse($envio->data_envio)->format('d/m/Y H:i') }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-app-layout>
