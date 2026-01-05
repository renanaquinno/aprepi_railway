<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Aprepi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col bg-gray-100">
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

    <!-- Navegação -->
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

        <!-- Conteúdo da Página -->
            <main class="flex-grow max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
            </main>
        </div>

        <!-- Rodapé -->
        <footer class="bg-gray-800 text-white text-center py-2">
            <p>&copy; {{ date('Y') }} Sysprepi - Todos os direitos reservados</p>
            <div class="text-sm text-gray-400 mt-2">
                <p>Siga-nos nas redes sociais:</p>
                    <div class="flex justify-center space-x-4 my-2">
                        <a href="#" class="hover:text-blue-600"><i class="fab fa-facebook"></i> Facebook</a>
                        <a href="#" class="hover:text-pink-600"><i class="fab fa-instagram"></i> Instagram<a>
                    </div>
            </div>
        </footer>
        @stack('scripts')
    </body>
</html>
