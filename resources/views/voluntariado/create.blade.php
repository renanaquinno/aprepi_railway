<x-app-layout>
    <x-page-header title="Voluntariado" :breadcrumbs="[['label' => 'Voluntariado']]" />
    @include('components.alert-messages')
    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            @php
            @endphp
            @if (auth()->check() && !in_array(auth()->user()->tipo_usuario, ['doador', 'socio']))
                <div class="py-2">
                    <div class="bg-white shadow-md rounded-lg p-8 text-center">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">
                            Você já faz parte do quadro de voluntários
                        </h2>
                        <p class="text-gray-600">
                            Caso precise atualizar suas informações, entre em contato com a administração.
                        </p>
                    </div>
                </div>
            @else
                <h2 class="text-2xl font-semibold mb-4">Seja um voluntário</h2>
                <p class="mb-6 italic">"A alma generosa prosperará e aquele que atende também será atendido."
                    <strong>Provérbios 11:25</strong>
                </p>
                <form method="POST" action="{{ route('voluntariado.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label value="Nome Completo" />
                        <x-text-input name="name" class="w-full" value="{{ old('name') }}" required />
                    </div>

                    <div>
                        <x-input-label value="Email" />
                        <x-text-input type="email" name="email" class="w-full" value="{{ old('email') }}"
                            required />
                    </div>

                    <div>
                        <x-input-label value="CPF" />
                        <x-text-input name="cpf" class="w-full" value="{{ old('cpf') }}" required />
                    </div>

                    <div>
                        <x-input-label value="Telefone" />
                        <x-text-input name="telefone" class="w-full" value="{{ old('telefone') }}" required />
                    </div>

                    <div>
                        <x-input-label value="Data de Nascimento" />
                        <x-text-input type="date" name="data_nascimento" class="w-full"
                            value="{{ old('data_nascimento') }}" required />
                    </div>

                    <div>
                        <x-input-label value="Senha" />
                        <x-text-input type="password" name="password" class="w-full" required />
                    </div>

                    <div>
                        <x-input-label value="Confirmar Senha" />
                        <x-text-input type="password" name="password_confirmation" class="w-full" required />
                    </div>

                    <div>
                        <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                            Solicitar Cadastro
                        </x-primary-button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
