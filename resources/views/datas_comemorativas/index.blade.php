<x-app-layout>

    <x-page-header 
        title="Datas Comemorativas"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Datas Comemorativas']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">

            {{-- Botão de criar nova data comemorativa --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                <a href="{{ route('datas_comemorativas.create') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    + Nova Data
                </a>
            </div>

            {{-- Mensagens de alerta --}}
            @include('components.alert-messages')

            {{-- Tabela --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Ord.</th>
                            <th class="px-4 py-2">Título</th>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Último envio</th>
                            <th class="px-4 py-2">Ações</th>
                            <th class="px-4 py-2 text-center">Enviar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($datas as $data)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $data->titulo }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->data)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    {{ optional($data->envios->last())->data_envio
                                        ? \Carbon\Carbon::parse($data->envios->last()->data_envio)->format('d/m/Y')
                                        : 'Nunca' }}
                                </td>

                                {{-- Ações --}}
                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('datas_comemorativas.show', $data) }}" 
                                       class="text-green-600 hover:text-green-800" title="Detalhes">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M4 4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M14 2v6h6"/>
                                        </svg>
                                    </a>

                                    <a href="{{ route('datas_comemorativas.edit', $data) }}" 
                                       class="text-blue-600 hover:text-blue-800" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('datas_comemorativas.destroy', $data) }}" method="POST"
                                          onsubmit="return confirm('Deseja excluir esta data comemorativa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>

                                {{-- Botão de envio sempre ativo --}}
                                {{-- Botão de envio com restrição de uma vez por ano --}}
                                <td class="px-4 py-2 text-center">
                                    @php
                                        $ultimoEnvio = $data->envios->last();
                                        $podeEnviar = true;

                                        if ($ultimoEnvio) {
                                            $podeEnviar = \Carbon\Carbon::parse($ultimoEnvio->data_envio)->year < now()->year;
                                        }
                                    @endphp

                                    @if($podeEnviar)
                                        <form action="{{ route('datas_comemorativas.enviarEmails', $data) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                Enviar Emails
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-sm">Já enviado este ano</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                    Nenhuma data comemorativa cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            <div class="mt-4">
                {{ $datas->links('vendor.pagination.tailwind-light') }}
            </div>

        </div>
    </div>

</x-app-layout>
