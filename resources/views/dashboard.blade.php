@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-sm text-gray-500 mt-1">Intake {{ auth()->user()->intake }} · {{ ucfirst(auth()->user()->shift) }} Shift</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content: Notices Feed --}}
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">Notices & Events</h2>

            @forelse ($notices as $notice)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $notice->type === 'event' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($notice->type) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $notice->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $notice->title }}</h3>
                    @if ($notice->event_date)
                        <p class="text-xs text-indigo-600 mt-1">📅 {{ $notice->event_date->format('M d, Y') }}</p>
                    @endif
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ Str::limit($notice->body, 200) }}</p>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 text-center text-sm text-gray-400">
                    No notices yet.
                </div>
            @endforelse
        </div>

        {{-- Sidebar: Notifications & Pending Approvals --}}
        <div class="space-y-6">
            {{-- Notifications --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Notifications
                    @if ($notifications->count())
                        <span class="ml-1 text-xs font-normal text-gray-400">({{ $notifications->count() }})</span>
                    @endif
                </h2>

                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse ($notifications as $notification)
                        <div class="bg-white rounded-xl border {{ $notification->read_at ? 'border-gray-200' : 'border-indigo-200 bg-indigo-50/30' }} shadow-sm p-4">
                            @if ($notification->type === 'App\\Notifications\\NewJobMatchesTagNotification')
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $notification->data['title'] ?? 'New Job' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Matches tag <span class="font-medium text-indigo-600">#{{ $notification->data['matched_tag'] ?? '' }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        @if (isset($notification->data['job_post_id']))
                                            <a href="{{ route('jobs.show', $notification->data['job_post_id']) }}"
                                               class="inline-block mt-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700">
                                                View Job →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">{{ json_encode($notification->data) }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 text-center text-sm text-gray-400">
                            No notifications yet. Follow tags to get notified about matching jobs!
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pending Approvals --}}
            <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Pending Approvals
                @if ($pendingReferrals->count())
                    <span class="ml-2 inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full">
                        {{ $pendingReferrals->count() }}
                    </span>
                @endif
            </h2>

            <div id="pending-approvals" class="space-y-3">
                @forelse ($pendingReferrals as $referral)
                    @php
                        $position = $referral->referencePosition(auth()->user()->email);
                        $alreadyApproved = $position === 1 ? $referral->reference_1_approved_at : $referral->reference_2_approved_at;
                        $otherApproved = $position === 1 ? $referral->reference_2_approved_at : $referral->reference_1_approved_at;
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4" data-user-id="{{ $referral->id }}">
                        <p class="font-medium text-gray-900 text-sm">{{ $referral->name }}</p>
                        <p class="text-xs text-gray-500">{{ $referral->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">Intake {{ $referral->intake }} · {{ ucfirst($referral->shift) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs {{ $alreadyApproved ? 'text-green-600' : 'text-gray-400' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $alreadyApproved ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                Your approval
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs {{ $otherApproved ? 'text-green-600' : 'text-gray-400' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $otherApproved ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                Other reference
                            </span>
                        </div>
                        @if ($alreadyApproved)
                            <p class="text-xs text-green-600 mt-3 font-medium">✓ You already approved this request.</p>
                        @else
                            <div class="flex gap-2 mt-3">
                                <button
                                    class="btn-confirm-action flex-1 text-xs font-medium py-1.5 px-3 rounded-lg bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition"
                                    data-action="approve"
                                    data-url="{{ route('approvals.approve', $referral) }}"
                                    data-name="{{ $referral->name }}">
                                    Approve
                                </button>
                                <button
                                    class="btn-confirm-action flex-1 text-xs font-medium py-1.5 px-3 rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition"
                                    data-action="decline"
                                    data-url="{{ route('approvals.reject', $referral) }}"
                                    data-name="{{ $referral->name }}">
                                    Decline
                                </button>
                            </div>
                        @endif
                        <p class="approval-message text-xs mt-2 hidden"></p>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 text-center text-sm text-gray-400">
                        No pending approvals.
                    </div>
                @endforelse
            </div>
        </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmation-modal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div id="modal-panel" class="relative bg-white rounded-2xl shadow-xl border border-gray-200 p-6" style="width: 100%; max-width: 380px;">
                {{-- Icon --}}
                <div id="modal-icon-wrapper" class="mx-auto flex h-12 w-12 items-center justify-center rounded-full mb-4 bg-green-100">
                    <svg id="modal-icon-approve" class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg id="modal-icon-decline" class="h-6 w-6 text-red-600" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                {{-- Content --}}
                <h3 id="modal-title" class="text-lg font-semibold text-gray-900 text-center">Confirm Action</h3>
                <p id="modal-description" class="mt-2 text-sm text-gray-500 text-center leading-relaxed">Are you sure?</p>

                {{-- Actions --}}
                <div class="flex gap-3 mt-6">
                    <button id="modal-cancel" type="button"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                        Cancel
                    </button>
                    {{-- Approve confirm button --}}
                    <button id="modal-confirm-approve" type="button" style="display:none; background-color:#16a34a;"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-lg transition cursor-pointer"
                        onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                        Yes, Approve
                    </button>
                    {{-- Decline confirm button --}}
                    <button id="modal-confirm-decline" type="button" style="display:none; background-color:#dc2626;"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-lg transition cursor-pointer"
                        onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                        Yes, Decline
                    </button>
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

