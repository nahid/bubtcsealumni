@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="animate-fade-in">
    {{-- Welcome Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <x-avatar :name="auth()->user()->name" size="lg" />
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
                <div class="flex items-center gap-2 mt-1">
                    <x-badge color="primary" size="sm">Intake {{ auth()->user()->intake }}</x-badge>
                    <x-badge color="neutral" size="sm">{{ ucfirst(auth()->user()->shift) }} Shift</x-badge>
                    @if (auth()->user()->alumni_id)
                        <span class="text-xs text-gray-400 font-mono">{{ auth()->user()->alumni_id }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content: Notices Feed --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Notices & Events</h2>
            </div>

            @forelse ($notices as $notice)
                <x-card :hover="true">
                    <div class="flex items-center gap-2 mb-3">
                        @if ($notice->type === 'event')
                            <x-badge color="info">
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Event
                            </x-badge>
                        @else
                            <x-badge color="neutral">
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                Notice
                            </x-badge>
                        @endif
                        <span class="text-xs text-gray-400">{{ $notice->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $notice->title }}</h3>
                    @if ($notice->event_date)
                        <p class="text-xs text-indigo-600 font-medium mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $notice->event_date->format('M d, Y') }}
                        </p>
                    @endif
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ Str::limit($notice->body, 200) }}</p>
                    @if ($notice->isEvent() && $notice->hasRegistrationForm())
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            @if (in_array($notice->id, $registeredEventIds))
                                <x-badge color="success" size="md">
                                    <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Registered
                                </x-badge>
                            @else
                                <x-button variant="primary" size="sm" :href="route('events.show', $notice)">
                                    Register Now
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </x-button>
                            @endif
                        </div>
                    @elseif ($notice->isEvent())
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('events.show', $notice) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                View Event Details
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    @endif
                </x-card>
            @empty
                <x-card>
                    <x-empty-state icon="bell" title="No notices yet" description="Check back later for announcements and events." />
                </x-card>
            @endforelse
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Notifications --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
                    @if ($notifications->count())
                        <x-badge color="neutral">{{ $notifications->count() }}</x-badge>
                    @endif
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @forelse ($notifications as $notification)
                        <x-card class="!p-4 {{ $notification->read_at ? '' : '!border-indigo-200 !bg-indigo-50/30' }}">
                            @if ($notification->type === 'App\\Notifications\\NewJobMatchesTagNotification')
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $notification->data['title'] ?? 'New Job' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Matches <x-badge color="primary" size="xs">#{{ $notification->data['matched_tag'] ?? '' }}</x-badge>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        @if (isset($notification->data['job_post_id']))
                                            <a href="{{ route('jobs.show', $notification->data['job_post_id']) }}"
                                               class="inline-flex items-center gap-1 mt-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                                View Job <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">{{ json_encode($notification->data) }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            @endif
                        </x-card>
                    @empty
                        <x-card class="!p-4">
                            <p class="text-sm text-gray-400 text-center">No notifications yet. Follow tags to get notified!</p>
                        </x-card>
                    @endforelse
                </div>
            </div>

            {{-- Pending Approvals --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Pending Approvals</h2>
                    @if ($pendingReferrals->count())
                        <x-badge color="danger" size="xs">{{ $pendingReferrals->count() }}</x-badge>
                    @endif
                </div>

                <div id="pending-approvals" class="space-y-3">
                    @forelse ($pendingReferrals as $referral)
                        @php
                            $position = $referral->referencePosition(auth()->user()->email);
                            $alreadyApproved = $position === 1 ? $referral->reference_1_approved_at : $referral->reference_2_approved_at;
                            $otherApproved = $position === 1 ? $referral->reference_2_approved_at : $referral->reference_1_approved_at;
                        @endphp
                        <x-card class="!p-4" data-user-id="{{ $referral->id }}">
                            <div class="flex items-center gap-3 mb-2">
                                <x-avatar :name="$referral->name" size="sm" />
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $referral->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $referral->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 mb-3">
                                <x-badge color="primary" size="xs">Intake {{ $referral->intake }}</x-badge>
                                <x-badge color="neutral" size="xs">{{ ucfirst($referral->shift) }}</x-badge>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="inline-flex items-center gap-1.5 {{ $alreadyApproved ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <span class="h-2 w-2 rounded-full {{ $alreadyApproved ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    Your approval
                                </span>
                                <span class="inline-flex items-center gap-1.5 {{ $otherApproved ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <span class="h-2 w-2 rounded-full {{ $otherApproved ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    Other reference
                                </span>
                            </div>
                            @if ($alreadyApproved)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <p class="text-xs text-emerald-600 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        You already approved this request.
                                    </p>
                                </div>
                            @else
                                <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                                    <button
                                        class="btn-confirm-action flex-1 text-xs font-medium py-2 px-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition cursor-pointer"
                                        data-action="approve"
                                        data-url="{{ route('approvals.approve', $referral) }}"
                                        data-name="{{ $referral->name }}">
                                        Approve
                                    </button>
                                    <button
                                        class="btn-confirm-action flex-1 text-xs font-medium py-2 px-3 rounded-xl bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition cursor-pointer"
                                        data-action="decline"
                                        data-url="{{ route('approvals.reject', $referral) }}"
                                        data-name="{{ $referral->name }}">
                                        Decline
                                    </button>
                                </div>
                            @endif
                            <p class="approval-message text-xs mt-2 hidden"></p>
                        </x-card>
                    @empty
                        <x-card class="!p-4">
                            <p class="text-sm text-gray-400 text-center">No pending approvals.</p>
                        </x-card>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmation-modal" class="fixed inset-0 z-50 hidden">
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div id="modal-panel" class="relative bg-white rounded-2xl shadow-xl border border-gray-200/60 p-6 w-full max-w-sm animate-fade-in">
                <div id="modal-icon-wrapper" class="mx-auto flex h-12 w-12 items-center justify-center rounded-full mb-4 bg-emerald-100">
                    <svg id="modal-icon-approve" class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg id="modal-icon-decline" class="h-6 w-6 text-red-600" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 id="modal-title" class="text-lg font-semibold text-gray-900 text-center">Confirm Action</h3>
                <p id="modal-description" class="mt-2 text-sm text-gray-500 text-center leading-relaxed">Are you sure?</p>
                <div class="flex gap-3 mt-6">
                    <button id="modal-cancel" type="button"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition cursor-pointer">
                        Cancel
                    </button>
                    <button id="modal-confirm-approve" type="button" style="display:none;"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition cursor-pointer focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Yes, Approve
                    </button>
                    <button id="modal-confirm-decline" type="button" style="display:none;"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl transition cursor-pointer focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Yes, Decline
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var modal = document.getElementById('confirmation-modal');
    var backdrop = document.getElementById('modal-backdrop');
    var iconWrapper = document.getElementById('modal-icon-wrapper');
    var iconApprove = document.getElementById('modal-icon-approve');
    var iconDecline = document.getElementById('modal-icon-decline');
    var modalTitle = document.getElementById('modal-title');
    var modalDescription = document.getElementById('modal-description');
    var btnConfirmApprove = document.getElementById('modal-confirm-approve');
    var btnConfirmDecline = document.getElementById('modal-confirm-decline');
    var modalCancel = document.getElementById('modal-cancel');

    var pendingUrl = null;
    var pendingCard = null;

    function openModal(action, name, url, card) {
        pendingUrl = url;
        pendingCard = card;

        var isApprove = action === 'approve';

        // Icon
        iconApprove.style.display = isApprove ? 'block' : 'none';
        iconDecline.style.display = isApprove ? 'none' : 'block';
        iconWrapper.style.backgroundColor = isApprove ? '#dcfce7' : '#fee2e2';

        // Text
        modalTitle.textContent = isApprove ? 'Approve Registration' : 'Decline Registration';
        modalDescription.textContent = isApprove
            ? 'Are you sure you want to approve ' + name + '\'s registration? This will count as your reference approval.'
            : 'Are you sure you want to decline ' + name + '\'s registration? This will permanently delete their account.';

        // Show the correct confirm button
        btnConfirmApprove.style.display = isApprove ? 'block' : 'none';
        btnConfirmDecline.style.display = isApprove ? 'none' : 'block';

        // Show modal
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
        pendingUrl = null;
        pendingCard = null;
    }

    function executeAction() {
        if (!pendingUrl || !pendingCard) return;

        var card = pendingCard;
        var url = pendingUrl;
        closeModal();

        // Disable buttons
        card.querySelectorAll('.btn-confirm-action').forEach(function (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.5';
            btn.style.cursor = 'not-allowed';
        });

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function (result) {
            var msgEl = card.querySelector('.approval-message');
            if (result.ok) {
                msgEl.style.display = 'block';
                msgEl.style.color = '#16a34a';
                msgEl.textContent = result.data.message;
                setTimeout(function () {
                    card.style.transition = 'opacity 0.3s';
                    card.style.opacity = '0';
                    setTimeout(function () { card.remove(); }, 300);
                }, 1500);
            } else {
                msgEl.style.display = 'block';
                msgEl.style.color = '#dc2626';
                msgEl.textContent = result.data.message || 'Something went wrong.';
                card.querySelectorAll('.btn-confirm-action').forEach(function (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                });
            }
        })
        .catch(function () {
            var msgEl = card.querySelector('.approval-message');
            msgEl.style.display = 'block';
            msgEl.style.color = '#dc2626';
            msgEl.textContent = 'Network error. Please try again.';
            card.querySelectorAll('.btn-confirm-action').forEach(function (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            });
        });
    }

    // Open modal on button click
    document.getElementById('pending-approvals').addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-confirm-action');
        if (!btn) return;
        var card = btn.closest('[data-user-id]');
        openModal(btn.dataset.action, btn.dataset.name, btn.dataset.url, card);
    });

    // Confirm buttons
    btnConfirmApprove.addEventListener('click', executeAction);
    btnConfirmDecline.addEventListener('click', executeAction);

    // Cancel / close
    modalCancel.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display !== 'none') {
            closeModal();
        }
    });

    // Initialize: ensure modal is hidden
    modal.style.display = 'none';
});
</script>
@endpush

