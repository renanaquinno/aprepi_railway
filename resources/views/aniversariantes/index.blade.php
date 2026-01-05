<x-app-layout>
    <x-page-header 
        title="Aniversariantes"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Aniversariantes']]" 
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
   
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @elseif(session('info'))
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded mb-4">
                    {{ session('info') }}
                </div>
            @endif

            <div class="mb-4">
                <h2 class="text-xl font-semibold">Aniversariantes de Hoje</h2>
            </div>

            @if($aniversariantes->isEmpty())
                <p>Não há aniversariantes hoje.</p>
            @else
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Nome</th>
                            <th class="px-4 py-2">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aniversariantes as $user)
                        <tr class="odd:bg-white even:bg-gray-50">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

            <div class="flex justify-end gap-4 mt-6">
                <x-secondary-button onclick="window.location='{{ route('aniversariantes.mensagem') }}'">
                    Ver Mensagem
                </x-secondary-button>

                @if($aniversariantes->isEmpty())
                    <x-primary-button disabled class="bg-gray-400 cursor-not-allowed">
                        Nenhum aniversariante hoje
                    </x-primary-button>
                @elseif($envioHoje)
                    <x-primary-button disabled class="bg-gray-400 cursor-not-allowed">
                        Emails já enviados hoje
                    </x-primary-button>
                @else
                    <form action="{{ route('aniversariantes.enviarEmails') }}" method="POST">
                        @csrf
                        <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                            Enviar Emails
                        </x-primary-button>
                    </form>
                @endif
            </div>


        </div>
    </div>
</x-app-layout>
