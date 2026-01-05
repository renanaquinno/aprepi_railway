<x-app-layout>
    @if ($errors->any())
    <div class="mb-4">
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <x-page-header 
        title="Gerenciamento de Usuários"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Usuários', 'url' => route('usuarios.index')],
            ['label' => isset($user) ? 'Editar' : 'Novo']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            @if ($errors->any())
    <div class="mb-4">
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form method="POST" action="{{ isset($user) ? route('usuarios.update', $user) : route('usuarios.store') }}">  
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nome --}}
                    <div>
                        <x-input-label value="Nome" />
                        <x-text-input class="w-full" name="name" 
                            value="{{ old('name', $user->name ?? '') }}" required />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label value="Email" />
                        <x-text-input class="w-full" type="email" name="email" 
                            value="{{ old('email', $user->email ?? '') }}" required />
                    </div>

                    {{-- CPF --}}
                    <div>
                        <x-input-label value="CPF" />
                        <x-text-input class="w-full" name="cpf" 
                            value="{{ old('cpf', $user->cpf ?? '') }}" required />
                    </div>

                    {{-- Data de Nascimento --}}
                    <div>
                        <x-input-label value="Data de Nascimento" />
                        <x-text-input class="w-full" type="date" name="data_nascimento" 
                        value="{{ old('data_nascimento', isset($user->data_nascimento) ? \Carbon\Carbon::parse($user->data_nascimento)->format('Y-m-d') : '') }}" 
                        required />
                    </div>

                    {{-- Telefone --}}
                    <div>
                        <x-input-label value="Telefone" />
                        <x-text-input class="w-full" name="telefone" 
                            value="{{ old('telefone', $user->telefone ?? '') }}" required />
                    </div>

                    {{-- Endereço --}}
                    <div>
                        <x-input-label value="Endereço" />
                        <x-text-input class="w-full" name="endereco" 
                            value="{{ old('endereco', $user->endereco ?? '') }}" required />
                    </div>

                    {{-- Cidade --}}
                    <div>
                        <x-input-label value="Cidade" />
                        <x-text-input class="w-full" name="cidade" 
                            value="{{ old('cidade', $user->cidade ?? '') }}" required />
                    </div>

                    {{-- Estado --}}
                    <div>
                        <x-input-label value="Estado (UF)" />
                        <x-text-input class="w-full" name="estado" maxlength="2" 
                            value="{{ old('estado', $user->estado ?? '') }}" required />
                    </div>

                    {{-- CEP --}}
                    <div>
                        <x-input-label value="CEP" />
                        <x-text-input class="w-full" name="cep" 
                            value="{{ old('cep', $user->cep ?? '') }}" required />
                    </div>
                   
                    {{-- Tipo de Usuário --}}
                    <div>
                        <x-input-label value="Tipo de Usuário" />
                        <select name="tipo_usuario" class="w-full border-gray-300 rounded"
                            @if(auth()->user()->isVoluntarioAdm()) disabled @endif>
                            @foreach(['admin', 'voluntario_adm', 'voluntario_ext', 'socio', 'doador'] as $tipo_usuario)
                                <option value="{{ $tipo_usuario }}" 
                                    {{ (old('tipo_usuario', $user->tipo_usuario ?? '') == $tipo_usuario) ? 'selected' : '' }}>
                                    {{
                                        $tipo_usuario == 'admin' ? 'Administrador' :
                                        ($tipo_usuario == 'voluntario_adm' ? 'Voluntário Administrativo' :
                                        ($tipo_usuario == 'voluntario_ext' ? 'Voluntário Externo' :
                                        ($tipo_usuario == 'socio' ? 'Sócio' :
                                        ($tipo_usuario == 'doador' ? 'Doador' : ucfirst($tipo_usuario)))))
                                    }}
                                </option>
                            @endforeach
                        </select>

                        @if(auth()->user()->isVoluntarioAdm())
                            <input type="hidden" name="tipo_usuario" value="{{ old('tipo_usuario', $user->tipo_usuario ?? '') }}">
                        @endif
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-input-label value="Status" />
                        <select name="ativo" class="w-full border-gray-300 rounded">
                            <option value="1" {{ (old('ativo', $user->ativo ?? 1) == 1) ? 'selected' : '' }}>
                                Ativo
                            </option>
                            <option value="0" {{ (old('ativo', $user->ativo ?? 1) == 0) ? 'selected' : '' }}>
                                Inativo
                            </option>
                        </select>
                    </div>

                    {{-- Senha (somente na criação) --}}
                    @if(!isset($user))
                        <div>
                            <x-input-label value="Senha" />
                            <x-text-input type="password" name="password" class="w-full" required />
                        </div>
                    @endif

                    {{-- Observações (ocupa toda largura) --}}
                    <div class="md:col-span-2">
                        <x-input-label value="Observações" />
                        <textarea name="observacoes" class="w-full border-gray-300 rounded">{{ old('observacoes', $user->observacoes ?? '') }}</textarea>
                    </div>

                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('usuarios.index') }}'">
                        Cancelar
                    </x-secondary-button>
                    
                    <x-primary-button type="submit" class="bg-sky-700 hover:bg-sky-900">
                        {{ isset($user) ? 'Atualizar' : 'Cadastrar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
