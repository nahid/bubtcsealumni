@props([
    'id',
    'title' => 'Confirm',
    'description' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'variant' => 'danger',
])

@php
    $variants = [
        'danger' => ['icon_bg' => 'bg-red-100', 'icon_color' => 'text-red-600', 'btn' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500'],
        'success' => ['icon_bg' => 'bg-emerald-100', 'icon_color' => 'text-emerald-600', 'btn' => 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500'],
        'warning' => ['icon_bg' => 'bg-amber-100', 'icon_color' => 'text-amber-600', 'btn' => 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500'],
    ];
    $config = $variants[$variant] ?? $variants['danger'];
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-200/60 p-6 w-full max-w-sm animate-fade-in">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full {{ $config['icon_bg'] }} mb-4">
                <svg class="h-6 w-6 {{ $config['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center">{{ $title }}</h3>
            <p class="mt-2 text-sm text-gray-500 text-center leading-relaxed">{{ $description }}</p>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition cursor-pointer">
                    {{ $cancelText }}
                </button>
                <button type="button" id="{{ $id }}-confirm"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-xl transition focus:outline-none focus:ring-2 focus:ring-offset-2 cursor-pointer {{ $config['btn'] }}">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>
