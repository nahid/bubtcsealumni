@props([
    'color' => 'neutral',
    'size' => 'sm',
])

@php
    $colors = [
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
        'danger' => 'bg-red-50 text-red-700 ring-red-600/10',
        'info' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
        'neutral' => 'bg-gray-100 text-gray-600 ring-gray-500/10',
        'primary' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/10',
    ];

    $sizes = [
        'xs' => 'text-[11px] px-1.5 py-0.5',
        'sm' => 'text-xs px-2 py-1',
        'md' => 'text-sm px-2.5 py-1',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center font-medium rounded-full ring-1 ring-inset',
    $colors[$color] ?? $colors['neutral'],
    $sizes[$size] ?? $sizes['sm'],
]) }}>
    {{ $slot }}
</span>
