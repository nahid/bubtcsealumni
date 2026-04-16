@props([
    'value' => 0,
    'label' => '',
    'color' => 'gray',
    'icon' => null,
])

@php
    $colors = [
        'gray' => 'text-gray-900',
        'green' => 'text-emerald-600',
        'yellow' => 'text-amber-600',
        'red' => 'text-red-600',
        'indigo' => 'text-indigo-600',
        'blue' => 'text-blue-600',
    ];
    $iconBgs = [
        'gray' => 'bg-gray-100 text-gray-500',
        'green' => 'bg-emerald-100 text-emerald-600',
        'yellow' => 'bg-amber-100 text-amber-600',
        'red' => 'bg-red-100 text-red-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'blue' => 'bg-blue-100 text-blue-600',
    ];
@endphp

<x-card {{ $attributes }}>
    <div class="flex items-center gap-4">
        @if ($icon)
            <div class="flex-shrink-0 w-11 h-11 rounded-xl {{ $iconBgs[$color] ?? $iconBgs['gray'] }} flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            </div>
        @endif
        <div>
            <p class="text-2xl font-bold {{ $colors[$color] ?? $colors['gray'] }}">{{ $value }}</p>
            <p class="text-sm text-gray-500 mt-0.5">{{ $label }}</p>
        </div>
    </div>
    @if ($slot->isNotEmpty())
        <div class="mt-2">{{ $slot }}</div>
    @endif
</x-card>
