<x-app-layout>
    <x-page-header 
        title="{{ isset($cesta) ? 'Editar Cesta' : 'Cadastrar Cesta' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Cestas', 'url' => route('cestas.index')],
            ['label' => isset($cesta) ? 'Editar' : 'Cadastrar']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            <form action="{{ isset($cesta) ? route('cestas.update', $cesta) : route('cestas.store') }}" method="POST">
                @csrf
                @if(isset($cesta))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Data de Recebimento --}}
                    <div>
                        <x-input-label value="Data de Recebimento" />
                        
                        <x-text-input type="date" name="data_recebimento"
                        value="{{ old('data_recebimento', isset($cesta) ? \Carbon\Carbon::parse($cesta->data_recebimento)->format('Y-m-d') : '') }}"
                        class="w-full" required />
                    </div>

                    {{-- Tipo de Entrada --}}
                    <div>
                        <x-input-label value="Tipo de Entrada" />
                        <select id="entrada_tipo" name="entrada_tipo" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="doacao" {{ old('entrada_tipo', $cesta->entrada_tipo ?? '') == 'doacao' ? 'selected' : '' }}>Doação</option>
                            <option value="compra" {{ old('entrada_tipo', $cesta->entrada_tipo ?? '') == 'compra' ? 'selected' : '' }}>Compra</option>
                        </select>
                    </div>

                    {{-- Origem (apenas para doação) --}}
                    <div id="origem_doacao_div">
                        <x-input-label value="Origem (quem doou)" />
                        <!-- Campo hidden para enviar o valor correto -->
                        <input type="hidden" name="origem" id="origem_hidden" value="{{ old('origem', $cesta->origem ?? '') }}">
                        
                        <select id="origem_doacao" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione um doador</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" 
                                    {{ old('origem', $cesta->origem ?? '') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <!-- Campo oculto enviado no form -->
                        <input type="hidden" name="status" value="{{ old('status', $cesta->status ?? 'disponivel') }}">

                        <x-input-label value="Status" />
                        <x-text-input 
                            class="w-full bg-gray-100 text-gray-700" 
                            name="status_display" 
                            value="{{ ucfirst(old('status', $cesta->status ?? 'disponível')) }}" 
                            readonly 
                        />
                    </div>

                    {{-- Data de Entrega --}}
                    <div>
                        <x-input-label value="Data de Entrega" />
                        <x-text-input 
                            type="date" 
                            name="data_entrega"
                            value="{{ old('data_entrega', isset($cesta) ? \Carbon\Carbon::parse($cesta->data_entrega)->format('Y-m-d') : '') }}"
                            class="w-full bg-gray-100 cursor-not-allowed" 
                            readonly
                            {{-- não required --}}
                        />
                    </div>

                    {{-- Entregue para (destinatário) --}}
                    <div>
                        <x-input-label value="Entregue para (destinatário)" />

                        <!-- Campo hidden para enviar o valor, pois select disabled não envia -->
                        <input type="hidden" name="user_id" value="{{ old('user_id', $cesta->user_id ?? '') }}">

                        <select disabled class="w-full border-gray-300 rounded shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed focus:ring-0 focus:border-gray-300">
                            <option value="">Não entregue</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" 
                                    {{ old('user_id', $cesta->user_id ?? '') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- Observações --}}
                <div class="mt-6">
                    <x-input-label value="Observações" />
                    <textarea name="observacoes" rows="3"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('observacoes', $cesta->observacoes ?? '') }}</textarea>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('cestas.index') }}'">
                        Cancelar
                    </x-secondary-button>
                    
                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        {{ isset($cesta) ? 'Atualizar' : 'Cadastrar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
        
<script>
document.addEventListener('DOMContentLoaded', function () {
    const entrada = document.getElementById('entrada_tipo');
    const origemSelect = document.getElementById('origem_doacao');
    const origemHidden = document.getElementById('origem_hidden');

    const fornecedorCompraId = '86'; // ID do usuário Fornecedor Compra

    function atualizarOrigem() {
        if (entrada.value === 'compra') {
            origemSelect.value = fornecedorCompraId; // seleciona automaticamente
            origemSelect.disabled = true; // não permite edição
            origemSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
            origemHidden.value = fornecedorCompraId; // envia no form
        } else {
            origemSelect.disabled = false;
            origemSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
            origemHidden.value = origemSelect.value; // envia o selecionado
        }
    }

    // Atualiza o hidden quando o usuário muda a seleção
    origemSelect.addEventListener('change', () => {
        origemHidden.value = origemSelect.value;
    });

    entrada.addEventListener('change', atualizarOrigem);
    atualizarOrigem(); // inicializa corretamente
});
</script>

</x-app-layout>