@props([
    'padding' => true,
    'hover' => false,
])

<div {{ $attributes->class([
    'bg-white rounded-2xl border border-gray-200/60 shadow-sm',
    'hover:shadow-md hover:border-gray-300/60 transition-all duration-200' => $hover,
    'p-6' => $padding,
]) }}>
    {{ $slot }}
</div>
