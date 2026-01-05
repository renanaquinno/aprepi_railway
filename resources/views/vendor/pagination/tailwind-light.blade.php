@if ($paginator->hasPages())
    <div class="flex flex-col md:flex-row justify-between items-center mt-4 space-y-2 md:space-y-0">
        {{-- Legenda --}}
        <div class="text-sm text-gray-600">
            Mostrando 
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            a
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-medium">{{ $paginator->total() }}</span>
            resultados
        </div>

        {{-- Navegação --}}
        <nav role="navigation" aria-label="Pagination Navigation">
            <ul class="inline-flex items-center space-x-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded cursor-default">&laquo;</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 rounded hover:bg-gray-100 transition">&laquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="px-3 py-1 text-gray-500 bg-gray-50 rounded cursor-default">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded cursor-default">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" class="px-3 py-1 bg-white text-gray-700 rounded hover:bg-gray-100 transition">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 rounded hover:bg-gray-100 transition">&raquo;</a>
                    </li>
                @else
                    <li>
                        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded cursor-default">&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
