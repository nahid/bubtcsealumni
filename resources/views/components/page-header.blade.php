@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->class(['flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4']) }}>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="flex items-center gap-3">{{ $slot }}</div>
    @endif
</div>
