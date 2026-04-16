@props([
    'label' => null,
    'name',
    'hint' => null,
    'error' => null,
    'rows' => 4,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->class([
            'w-full rounded-xl border px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition-all duration-150 resize-y',
            'border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20' => !$error,
            'border-red-300 focus:border-red-500 focus:ring-2 focus:ring-red-500/20' => $error,
        ]) }}
    >{{ $slot }}</textarea>
    @if ($error)
        <p class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
    @elseif ($hint)
        <p class="mt-1.5 text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
