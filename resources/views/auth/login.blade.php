<x-guest-layout>
  <div class="items-center flex justify-center bg-gray-100 pt-8">
    <div class="shadow-md px-6 py-4 pt-8 rounded-md bg-white rounded-xl shadow-2xl w-full max-w-md">

      <!-- Título / Subtítulo -->
      <h2 class="text-2xl font-semibold text-gray-700 text-center mb-1">
        Bem vindo
      </h2>
      <p class="text-sm text-gray-500 text-center mb-6">Preencha os dados do login para acessar</p>

      <!-- Status de Sessão (Laravel) -->
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

      <!-- Formulário -->
      <form action="{{ route('login') }}" method="POST" class="afgs space-y-6">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <div class=" relative">
            <input id="email"
                   name="email"
                   type="email"
                   required
                   autocomplete="email"
                   value="{{ old('email') }}"
                   class="rounded-md block w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none pl-2 pr-10 py-3 text-gray-700"
                   placeholder="seu@email.com" >
            <!-- Ícone à direita -->
            
          </div>
          <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Senha -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
          <div class=" relative">
            <input id="password"
                   name="password"
                   type="password"
                   required
                   autocomplete="current-password"
                   class="rounded-md block w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none pl-2 pr-10 py-3 text-gray-700"
                   placeholder="••••••••">
            <!-- Toggle mostrar/ocultar -->
           
          </div>
          <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Remember + Forgot -->
        <div class=" afgn afgo flex items-center justify-between">
          <label class="flex items-center gap-2">
            <input id="remember" type="checkbox" name="remember" class="rounded-md">
            <span class="text-sm text-gray-600">Lembrar-me</span>
          </label>

          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="rounded-md text-sm text-blue-600 hover:underline">Esqueceu a senha?</a>
          @endif
        </div>

        <!-- Botão Entrar -->
        <div>
          <button type="submit" class="rounded-md w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md shadow-md transition">
            Entrar
          </button>
        </div>
      </form>

</x-guest-layout>
