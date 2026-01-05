<x-app-layout>
    <x-page-header 
        title="Entregar Cesta"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Cestas', 'url' => route('cestas.index')],
            ['label' => 'Entregar']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">

            <h2 class="text-2xl font-semibold mb-6">Entregar Cesta #{{ $cesta->id }}</h2>

            <form method="POST" action="{{ route('cestas.entregar', $cesta) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Destinatário --}}
                    <div>
                        <x-input-label for="user_id" value="Destinatário" />
                        <select name="user_id" id="user_id" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Selecione um usuário</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $cesta->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Data de Entrega --}}
                    <div>
                        <x-input-label for="data_entrega" value="Data de Entrega" />
                        <x-text-input type="date" name="data_entrega" id="data_entrega"
                            value="{{ old('data_entrega', now()->format('Y-m-d')) }}" class="w-full" required />
                    </div>
                </div>

                {{-- Observações --}}
                <div class="mt-6">
                    <x-input-label for="observacoes" value="Observações" />
                    <textarea name="observacoes" id="observacoes" rows="3"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('observacoes', $cesta->observacoes) }}</textarea>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('cestas.index') }}'">
                        Cancelar
                    </x-secondary-button>
                    
                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        Confirmar Entrega
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
