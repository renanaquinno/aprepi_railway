

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Atualize suas informações de perfil e endereço de e-mail.') }}
        </p>
    </header>
@include('components.alert-messages')
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nome --}}
            <div>
                <x-input-label for="name" :value="__('Nome')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            {{-- Tipo Usuario --}}
            <div>
                <x-input-label for="tipo_usuario" :value="__('Tipo de Usuário')" />
                <input id="tipo_usuario" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" 
                    value="{{ $user->tipo_usuario_label }}" readonly />
            </div>

            {{-- CPF --}}
            <div>
                <x-input-label for="cpf" :value="__('CPF')" />
                <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full"
                    :value="old('cpf', $user->cpf)" required />
                <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
            </div>

            {{-- Data de Nascimento --}}
            <div>
                <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
                <x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full"
                    value="{{ old('data_nascimento', isset($user->data_nascimento) ? \Carbon\Carbon::parse($user->data_nascimento)->format('Y-m-d') : '') }}" required />
                <x-input-error class="mt-2" :messages="$errors->get('data_nascimento')" />
            </div>

            {{-- Telefone --}}
            <div>
                <x-input-label for="telefone" :value="__('Telefone')" />
                <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full"
                    :value="old('telefone', $user->telefone)" required />
                <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
            </div>

            {{-- Endereço --}}
            <div>
                <x-input-label for="endereco" :value="__('Endereço')" />
                <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full"
                    :value="old('endereco', $user->endereco)" required />
                <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
            </div>

            {{-- Cidade --}}
            <div>
                <x-input-label for="cidade" :value="__('Cidade')" />
                <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full"
                    :value="old('cidade', $user->cidade)" required />
                <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
            </div>

            {{-- Estado --}}
            <div>
                <x-input-label for="estado" :value="__('Estado (UF)')" />
                <x-text-input id="estado" name="estado" type="text" maxlength="2" class="mt-1 block w-full"
                    :value="old('estado', $user->estado)" required />
                <x-input-error class="mt-2" :messages="$errors->get('estado')" />
            </div>

            {{-- CEP --}}
            <div>
                <x-input-label for="cep" :value="__('CEP')" />
                <x-text-input id="cep" name="cep" type="text" class="mt-1 block w-full"
                    :value="old('cep', $user->cep)" required />
                <x-input-error class="mt-2" :messages="$errors->get('cep')" />
            </div>

            {{-- Observações --}}
            <div class="md:col-span-2">
                <x-input-label for="observacoes" :value="__('Observações')" />
                <textarea id="observacoes" name="observacoes" 
                    class="w-full border-gray-300 rounded">{{ old('observacoes', $user->observacoes) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('observacoes')" />
            </div>
        </div>

        {{-- Botões --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>
