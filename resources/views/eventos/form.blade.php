<x-app-layout>
    <x-page-header 
        :title="isset($evento) ? 'Editar Evento' : 'Novo Evento'"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Eventos', 'url' => route('eventos.index')],
            ['label' => isset($evento) ? 'Editar' : 'Novo']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            <form action="{{ isset($evento) ? route('eventos.update', $evento) : route('eventos.store') }}" method="POST">
                @csrf
                @if(isset($evento))
                    @method('PUT')
                @endif

                @php
                    $participantesSelecionados = old('participantes', isset($evento) 
                        ? $evento->participantes->whereIn('tipo_usuario', ['voluntario_adm', 'voluntario_ext'])->pluck('id')->toArray() 
                        : []);
                @endphp


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Título --}}
                    <div>
                        <x-input-label value="Título" />
                        <x-text-input class="w-full" name="titulo"
                            value="{{ old('titulo', $evento->titulo ?? '') }}" required />
                    </div>

                    {{-- Data e Hora --}}
                    <div>
                        <x-input-label value="Data e Hora" />
                        <x-text-input type="datetime-local" class="w-full" name="data_hora"
                            value="{{ old('data_hora', isset($evento) ? \Carbon\Carbon::parse($evento->data_hora)->format('Y-m-d\TH:i') : '') }}"
                            required />
                    </div>

                    {{-- Local --}}
                    <div>
                        <x-input-label value="Local" />
                        <x-text-input class="w-full" name="local"
                            value="{{ old('local', $evento->local ?? '') }}" required />
                    </div>

                    {{-- Valor Custo --}}
                    <div>
                        <x-input-label value="Valor Custo" />
                        <x-text-input type="number" step="0.01" class="w-full" name="valor_custo"
                            value="{{ old('valor_custo', $evento->valor_custo ?? '') }}" required />
                    </div>

                    {{-- Valor Arrecadado --}}
                    <div>
                        <x-input-label value="Valor Arrecadado" />
                        <x-text-input type="number" step="0.01" class="w-full" name="valor_arrecadado"
                            value="{{ old('valor_arrecadado', $evento->valor_arrecadado ?? '') }}" />
                    </div>

                    {{-- Evento Recorrente --}}
                    <div class="flex items-center mt-6">
                        <input type="hidden" name="recorrente" value="0">
                        <input type="checkbox" name="recorrente" id="recorrente"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            value="1"
                            {{ old('recorrente', $evento->recorrente ?? false) ? 'checked' : '' }}>

                        <label for="recorrente" class="ml-2 text-sm text-gray-700">Evento Recorrente</label>
                    </div>

                    {{-- Participantes --}}
                    <div class="md:col-span-2">
                        <x-input-label for="participantes" value="Participantes (Voluntários)" />
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($usuarios as $usuario)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="participantes[]" value="{{ $usuario->id }}"
                                {{ in_array($usuario->id, $participantesSelecionados) ? 'checked' : '' }}>

                                    <span>{{ $usuario->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Descrição --}}
                    <div class="md:col-span-2">
                        <x-input-label value="Descrição" />
                        <textarea name="descricao" rows="3"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('descricao', $evento->descricao ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('eventos.index') }}'">
                        Cancelar
                    </x-secondary-button>
                    
                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        {{ isset($evento) ? 'Atualizar' : 'Cadastrar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
