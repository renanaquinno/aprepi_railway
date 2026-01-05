<div class="py-2">
    <div class="bg-white rounded-md mb-6 p-4">
        @if(isset($title))
            <h2 class="text-xl font-semibold mb-4 text-gray-800">
                {{ $title }}
            </h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{ $slot }}
        </div>
    </div>
</div>
