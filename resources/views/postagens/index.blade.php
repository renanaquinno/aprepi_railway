<x-app-layout>
    <x-page-header 
        title="Postagens"
        :breadcrumbs="[
            ['label' => 'Postagens']
        ]"
    />

    <div class="py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($postagens as $postagem)
                <article class="bg-white shadow rounded-lg overflow-hidden">
                    {{-- Imagem da postagem --}}
                    @php         
                            preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $postagem->conteudo, $matches);
                            $imagemCapa = $matches['src'] ?? 'https://via.placeholder.com/400x250';
                    @endphp

                    <img src="{{ $imagemCapa }}" alt="{{ $postagem->titulo }}" class="w-full h-48 object-cover">


                    <div class="p-4">
                        {{-- Data e categoria --}}
                        <div class="flex items-center text-sm text-gray-500 space-x-2 mb-2">
                            <span> {{ \Carbon\Carbon::parse($postagem->created_at)->translatedFormat('d \d\e F \d\e Y') }}</span>
                           

                            <span class="px-2">•</span>
                            <span class="capitalize">{{ $postagem->categoria ?? 'Sem categoria' }}</span>
                        </div>

                        {{-- Título --}}
                        <h2 class="text-lg font-semibold text-gray-800 hover:text-indigo-600 hover:underline">
                            <a href="{{ route('postagens.show', $postagem) }}">
                                {{ $postagem->titulo }}
                            </a>
                        </h2>

                        {{-- Resumo --}}
                        <p class="mt-2 text-gray-600 text-sm">
                            {{ Str::limit(strip_tags($postagem->conteudo), 120, '...') }}
                        </p>

                       
                    </div>
                </article>
            @empty
                <p class="text-gray-500 col-span-3">Nenhuma postagem publicada até o momento.</p>
            @endforelse
        </div>

        {{-- Paginação --}}
        <div class="mt-6">
            {{ $postagens->links() }}
        </div>
    </div>
</x-app-layout>
