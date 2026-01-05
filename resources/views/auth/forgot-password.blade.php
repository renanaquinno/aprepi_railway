<x-guest-layout>
  <div class="items-center flex justify-center bg-gray-100 pt-8">
    <div class="shadow-md p-6 pt-8 rounded-xl bg-white shadow-2xl w-full max-w-md">

      <!-- Título / Subtítulo -->
      <h2 class="text-2xl font-semibold text-gray-700 text-center mb-1">
        Recuperar Senha
      </h2>
      <p class="text-sm text-gray-500 text-center mb-6">
        Informe seu e-mail e enviaremos um link para redefinir sua senha.
      </p>

      <!-- Status de Sessão -->
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

      <!-- Formulário -->
      <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input id="email"
                 type="email"
                 name="email"
                 value="{{ old('email') }}"
                 required
                 autofocus
                 class="rounded-md block w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none pl-2 pr-10 py-3 text-gray-700"
                 placeholder="seu@email.com">
          <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Botão -->
        <div>
          <button type="submit"
                  class="w-full rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 shadow-md transition">
            Enviar Link de Redefinição
          </button>
        </div>
      </form>

    </div>
  </div>
</x-guest-layout>
