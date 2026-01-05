<x-app-layout>
    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4 text-center">
            @if ($status === 'success')
                <h2 class="text-2xl font-semibold text-green-600">{{ $mensagem }}</h2>
            @elseif ($status === 'pending')
                <h2 class="text-2xl font-semibold text-yellow-600">{{ $mensagem }}</h2>
            @else
                <h2 class="text-2xl font-semibold text-red-600">{{ $mensagem }}</h2>
            @endif

            <a href="{{ route('doacoes.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Voltar ao site
            </a>
        </div>
    </div>
</x-app-layout>
