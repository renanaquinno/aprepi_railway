<x-app-layout>
    <x-page-header 
        title="Gerenciamento de Usuários"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Usuários']
        ]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            {{-- Botão + Filtros --}}
            <div class="flex justify-start items-center mb-4 gap-4">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('usuarios.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Novo Usuário
                </a>
                @endif

                <a href="{{ route('usuarios.relatorio.pdf', request()->query()) }}" 
                    target="_blank"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📄 Gerar PDF
                </a>
            </div>

                <form method="GET" class="flex gap-2 flex justify-between ">
                    <div class="flex gap-4">
                        <div>
                        <input type="text" name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Nome ou email"
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"></div>
                        
                        <div><label>Tipo de Usuário</label>
                            <select name="tipo_usuario" 
                            class="rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="admin" {{ request('tipo_usuario') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="voluntario_adm" {{ request('tipo_usuario') == 'voluntario_adm' ? 'selected' : '' }}>Voluntário Administrativo</option>
                            <option value="voluntario_ext" {{ request('tipo_usuario') == 'voluntario_ext' ? 'selected' : '' }}>Voluntário Externo</option>
                            <option value="socio" {{ request('tipo_usuario') == 'socio' ? 'selected' : '' }}>Sócio</option>
                            <option value="doador" {{ request('tipo_usuario') == 'doador' ? 'selected' : '' }}>Doador</option>
                        </select>
                        </div>
                    </div>
                    

                    <div class="flex gap-4">
                        <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                            Filtrar 
                        </x-primary-button>
                        <x-secondary-button onclick="window.location='{{ route('usuarios.index') }}'">
                            Limpar
                        </x-secondary-button>
                    </div>
                    
                        
                </form>
            </div>
          
        {{-- Tabela --}}
        @include('components.alert-messages')
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-slate-300">
                        <tr>
                            <th class="px-4 py-2 text-left">Ord.</th>
                            <th class="px-4 py-2 text-left">Nome</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">CPF</th>
                            <th class="px-4 py-2 text-left">Tipo de Usuário</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                                <td class="px-4 py-2"> {{ ($usuarios->currentPage() - 1) * $usuarios->perPage() + $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $usuario->name }}</td>
                                <td class="px-4 py-2">{{ $usuario->email }}</td>
                                <td class="px-4 py-2">{{ $usuario->cpf }}</td>
                                <td class="px-4 py-2">
                                    @switch($usuario->tipo_usuario)
                                        @case('voluntario_ext')
                                            Voluntário Externo
                                            @break
                                        @case('voluntario_adm')
                                            Voluntário Administrativo
                                            @break
                                        @case('admin')
                                            Administrador
                                            @break
                                        @case('socio')
                                            Sócio
                                            @break
                                        @case('doador')
                                            Doador
                                            @break
                                        @default
                                            {{ ucfirst($usuario->tipo_usuario) }}
                                    @endswitch
                                </td>
                                <td class="px-4 py-2 capitalize">{{ $usuario->ativo? 'Ativo' : 'Inativo' }}</td>
                                
                                <td class="px-4 py-2 flex space-x-2">
                                {{-- Detalhes (Arquivo) --}}
                                <a href="{{ route('usuarios.show', $usuario) }}" 
                                class="text-green-600 hover:text-green-800" 
                                title="Detalhes">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M4 4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M14 2v6h6"/>
                                    </svg>
                                </a>

                                {{-- Editar (Pincel) --}}
                                <a href="{{ route('usuarios.edit', $usuario) }}" 
                                class="text-blue-600 hover:text-blue-800" 
                                title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15.232 5.232l3.536 3.536M9 13L3 21h8l11-11a2.828 2.828 0 00-4-4L9 13z"/>
                                    </svg>
                                </a>

                                {{-- Excluir (Lixeira) --}}
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" 
                                        onsubmit="return confirm('Deseja excluir este usuário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Excluir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            <div class="mt-4">
                {{$usuarios->links('vendor.pagination.tailwind-light') }}
            </div>
        </div>
    </div>
</x-app-layout>
