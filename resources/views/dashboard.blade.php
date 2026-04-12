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

        {{-- Sidebar: Pending Approvals --}}
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
                                    class="btn-approve flex-1 text-xs font-medium py-1.5 px-3 rounded-lg bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition"
                                    data-url="{{ route('approvals.approve', $referral) }}">
                                    Approve
                                </button>
                                <button
                                    class="btn-reject flex-1 text-xs font-medium py-1.5 px-3 rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition"
                                    data-url="{{ route('approvals.reject', $referral) }}">
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
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#pending-approvals').on('click', '.btn-approve, .btn-reject', function () {
        const $btn = $(this);
        const $card = $btn.closest('[data-user-id]');
        const url = $btn.data('url');

        $card.find('.btn-approve, .btn-reject').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            dataType: 'json',
            success: function (data) {
                $card.find('.approval-message')
                    .removeClass('hidden text-red-600')
                    .addClass('text-green-600')
                    .text(data.message);

                setTimeout(function () {
                    $card.slideUp(300, function () { $(this).remove(); });
                }, 1500);
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Something went wrong.';
                $card.find('.approval-message')
                    .removeClass('hidden text-green-600')
                    .addClass('text-red-600')
                    .text(message);

                $card.find('.btn-approve, .btn-reject').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
            }
        });
    });
});
</script>
@endpush

