<x-app-layout>
    @php
        $breadcrumbs = [
            ['label' => 'Doações', 'url' => route('doacoes.index')],
            ['label' => isset($doacao) ? 'Editar' : 'Novo'],
        ];

        if (in_array(auth()->user()->tipo_usuario, ['admin', 'voluntario_adm'])) {
            array_unshift($breadcrumbs, ['label' => 'Dashboard', 'url' => route('dashboard')]);
        }
    @endphp

    <x-page-header title="{{ isset($doacao) ? 'Editar Doação' : 'Cadastrar Doação' }}"
         :breadcrumbs="$breadcrumbs" />
           
    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            <form action="{{ isset($doacao) ? route('doacoes.update', $doacao) : route('doacoes.store') }}"
                method="POST">
                @csrf
                @if (isset($doacao))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Doador --}}
                    <div>
                        <x-input-label for="user_id" value="Doador" />
                        @if (in_array(auth()->user()->tipo_usuario, ['admin', 'voluntario_adm']))
                            <select name="user_id" id="user_id" required
                                class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Selecione um doador</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}"
                                        {{ old('user_id', $doacao->user_id ?? '') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <x-text-input type="text" value="{{ auth()->user()->name }}" class="w-full bg-gray-100"
                                disabled />
                        @endif
                    </div>

                    {{-- Data --}}
                    <div>
                        <x-input-label for="data_doacao" value="Data" />
                        <x-text-input type="date" name="data_doacao"
                            value="{{ old('data_doacao', isset($doacao) ? \Carbon\Carbon::parse($doacao->data_doacao)->format('Y-m-d') : \Carbon\Carbon::today()->format('Y-m-d')) }}"
                            class="w-full" required />
                    </div>

                    {{-- Valor --}}
                    <div>
                        <x-input-label for="valor" value="Valor (R$)" />
                        <x-text-input type="number" step="0.01" name="valor"
                            value="{{ old('valor', $doacao->valor ?? '') }}" class="w-full" required />
                    </div>

                    {{-- Forma de Pagamento --}}
                    <div>
                        <x-input-label for="forma_pagamento" value="Forma de Pagamento" />
                        <select id="forma_pagamento" name="forma_pagamento"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Selecione uma forma de pagamento</option>
                            <option value="boleto"
                                {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'boleto' ? 'selected' : '' }}>
                                Boleto</option>
                            <option value="cartao"
                                {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'cartao' ? 'selected' : '' }}>
                                Cartão</option>
                            <option value="pix"
                                {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'pix' ? 'selected' : '' }}>
                                Pix</option>
                            <option value="transferencia"
                                {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'transferencia' ? 'selected' : '' }}>
                                Transferência</option>
                            <option value="especie"
                                {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'especie' ? 'selected' : '' }}>
                                Espécie</option>
                        </select>
                    </div>
                    @if (in_array(auth()->user()->tipo_usuario, ['admin', 'voluntario_adm']))
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select name="status" id="status"
                                class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="pendente"
                                    {{ old('status', $doacao->status ?? '') == 'pendente' ? 'selected' : '' }}>Pendente
                                </option>
                                <option value="realizada"
                                    {{ old('status', $doacao->status ?? '') == 'realizada' ? 'selected' : '' }}>
                                    Realizada</option>
                                <option value="cancelada"
                                    {{ old('status', $doacao->status ?? '') == 'cancelada' ? 'selected' : '' }}>
                                    Cancelada</option>
                            </select>
                        </div>
                    @endif


                    {{-- Informações adicionais --}}
                    <div id="infoEspecie"
                        class="mt-2 p-4 bg-gray-100 rounded {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'especie' ? '' : 'hidden' }}">
                        <strong>Doação em espécie:</strong> Dirija-se à Associação APREPI no endereço: Rua Exemplo, 123,
                        Cidade - Estado.
                    </div>
                    <div id="infoTransferencia"
                        class="mt-2 p-4 bg-gray-100 rounded {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'transferencia' ? '' : 'hidden' }}">
                        <strong>Transferência bancária:</strong> Banco XYZ, Agência 0001, Conta: 123456-7, Titular:
                        Associação APREPI.
                    </div>
                    <div id="infoPix"
                        class="mt-2 p-4 bg-gray-100 rounded {{ old('forma_pagamento', $doacao->forma_pagamento ?? '') == 'pix' ? '' : 'hidden' }}">
                        <br>
                        <img src="{{ asset('images/pix.png') }}" alt="QR Code Pix APREPI">
                    </div>

                    {{-- Observações --}}
                    <div class="md:col-span-2">
                        <x-input-label for="observacoes" value="Observações" />
                        <textarea name="observacoes" id="observacoes" rows="3"
                            class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('observacoes', $doacao->observacoes ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('doacoes.index') }}'"
                        type="button">Cancelar</x-secondary-button>

                    <input type="hidden" name="acao" id="acao" value="">

                    <button id="btnCadastrarPagar" type="submit"
                        class="bg-sky-800 hover:bg-sky-900 text-white px-4 py-2 rounded-md relative">
                        <span id="btnText">{{ isset($doacao) ? 'Atualizar' : 'Cadastrar e Pagar' }}</span>
                        <svg id="btnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white inline-block"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Spinner e submissão
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const btn = document.getElementById('btnCadastrarPagar');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const hiddenAcao = document.getElementById('acao');

            btn.addEventListener('click', function() {
                hiddenAcao.value = 'cadastrar_pagar';
            });

            form.addEventListener('submit', function(e) {
                if (btn.disabled) {
                    e.preventDefault();
                    return;
                }
                btn.disabled = true;
                btnText.textContent = 'Aguarde, redirecionando...';
                btnSpinner.classList.remove('hidden');
            });
        });

        // Mostrar info de pagamento
        const formaPagamento = document.getElementById('forma_pagamento');
        const infoEspecie = document.getElementById('infoEspecie');
        const infoTransferencia = document.getElementById('infoTransferencia');
        const infoPix = document.getElementById('infoPix');

        formaPagamento.addEventListener('change', function() {
            infoEspecie.classList.add('hidden');
            infoTransferencia.classList.add('hidden');
            infoPix.classList.add('hidden');

            if (this.value === 'especie') infoEspecie.classList.remove('hidden');
            if (this.value === 'transferencia') infoTransferencia.classList.remove('hidden');
            if (this.value === 'pix') infoPix.classList.remove('hidden');
        });
    </script>
</x-app-layout>
