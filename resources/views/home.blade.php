<x-app-layout>

    <div class="bg-white rounded-md mb-6">

        {{-- Bloco de Doações PIX --}}
        <div class="bg-white-50 border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-3">Ajude nossa causa</h2>
            <p class="text-gray-700 mb-4">
                Contribua com qualquer valor e nos ajude a continuar realizando nosso trabalho.
            </p>

            <div class="flex justify-center">
                <img src="{{ asset('images/pix.png') }}" alt="QR Code para doações"
                    class="w-40 h-40 object-contain">
            </div>

            <p class="text-center text-sm text-gray-600 mt-4">
                Aponte a câmera do seu celular para o QR Code para realizar uma doação via PIX.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-md mb-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            {{-- Imagem Central --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/voluntariado.png') }}" alt="Voluntariado" class="w-1/2 object-contain">
            </div>

            {{-- Título --}}
            <h1 class="text-3xl sm:text-4xl font-bold text-blue-700 mb-4">
                Seja um voluntário
            </h1>

            {{-- Versículo --}}
            <p class="text-lg text-gray-700 mb-6">
                A alma generosa prosperará e aquele que atende também será atendido. <br>
                <span class="italic text-gray-500">Provérbios 11:25</span>
            </p>

            {{-- Botão de Ação --}}
            <a href="{{ route('voluntariado.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-md shadow-md transition">
                Solicitar cadastro para voluntário
            </a>

        </div>
    </div>
</x-app-layout>
