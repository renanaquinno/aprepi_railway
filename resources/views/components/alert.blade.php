@props(['type' => 'success', 'message'])

@php
    $colors = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'yellow',
        'info' => 'blue',
    ];
    $color = $colors[$type] ?? 'green';
@endphp

@if ($message)
    <div 
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 5000)"
        class="relative mb-4 rounded-md border border-{{ $color }}-400 bg-{{ $color }}-100 px-4 py-3 text-{{ $color }}-700"
        role="alert"
    >
        <strong class="font-medium capitalize">
            @if($type == 'success') 
            @elseif($type == 'error') Erro!
            @elseif($type == 'warning') Atenção!
            @elseif($type == 'info') Informação:
            @endif
        </strong>
        <span class="block sm:inline ml-2">{{ $message }}</span>

        <button 
            @click="show = false"
            class="absolute top-2 right-2 text-{{ $color }}-700 hover:text-{{ $color }}-900"
        >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" 
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 
                      1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 
                      01-1.414 1.414L10 11.414l-4.293 4.293a1 1 
                      0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 
                      0 010-1.414z" 
                      clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
@endif
