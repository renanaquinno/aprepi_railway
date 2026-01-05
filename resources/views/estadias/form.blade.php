<x-app-layout>
    <x-page-header 
        :title="isset($estadia) ? 'Editar Estadia' : 'Nova Estadia'"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Estadias', 'url' => route('estadias.index')],
            ['label' => isset($estadia) ? 'Editar' : 'Nova']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            <form method="POST" action="{{ isset($estadia) ? route('estadias.update', $estadia) : route('estadias.store') }}">
                @csrf
                @if (isset($estadia))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Usuário --}}
                    <div>
                        <x-input-label value="Usuário" for="user_id" />
                        <select name="user_id" id="user_id" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="">Selecione um Usuário</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ old('user_id', $estadia->user_id ?? '') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Data de Início --}}
                    <div>
                        <x-input-label value="Data de Início" />
                        <x-text-input type="date" name="data_inicio" class="w-full"
                            value="{{ old('data_inicio', $estadia->data_inicio ?? '') }}" required />
                    </div>

                    {{-- Data de Fim --}}
                    <div>
                        <x-input-label value="Data de Fim" />
                        <x-text-input type="date" name="data_fim" class="w-full"
                            value="{{ old('data_fim', $estadia->data_fim ?? '') }}" />
                    </div>

                    {{-- Observações --}}
                    <div class="md:col-span-2">
                        <x-input-label value="Observações" />
                        <textarea name="observacoes" rows="3" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
{{ old('observacoes', $estadia->observacoes ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('estadias.index') }}'">
                        Cancelar
                    </x-secondary-button>

                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        {{ isset($estadia) ? 'Atualizar' : 'Cadastrar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
