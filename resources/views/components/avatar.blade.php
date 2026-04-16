@props([
    'name' => null,
    'src' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-[10px]',
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-14 h-14 text-lg',
        'xl' => 'w-20 h-20 text-2xl',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];

    $initials = '';
    if ($name) {
        $parts = explode(' ', trim($name));
        $initials = strtoupper(substr($parts[0], 0, 1));
        if (count($parts) > 1) {
            $initials .= strtoupper(substr(end($parts), 0, 1));
        }
    }
@endphp

@if ($src)
    <img src="{{ $src }}" alt="{{ $name }}" {{ $attributes->class(["rounded-full object-cover {$sizeClass}"]) }} />
@else
    <div {{ $attributes->class(["rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-semibold text-white {$sizeClass}"]) }}>
        {{ $initials }}
    </div>
@endif
