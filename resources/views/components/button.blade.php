@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'submit',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-medium rounded-xl transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 cursor-pointer';

    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500 shadow-sm',
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-indigo-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm',
        'ghost' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:ring-gray-500',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 shadow-sm',
    ];

    $sizes = [
        'xs' => 'text-xs px-2.5 py-1.5',
        'sm' => 'text-sm px-3 py-2',
        'md' => 'text-sm px-4 py-2.5',
        'lg' => 'text-base px-5 py-3',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes]) }}>{{ $slot }}</button>
@endif
