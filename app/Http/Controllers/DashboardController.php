<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function __invoke(): View
    {
        $user = auth()->user();

        $pendingReferrals = $user->pendingReferrals()->get();

        $notices = Notice::where('is_published', true)
            ->latest()
            ->take(10)
            ->get();

        $registeredEventIds = $user->eventRegistrations()->pluck('notice_id')->toArray();

        $notifications = $user->notifications()->latest()->take(20)->get();

        // Mark unread notifications as read
        $user->unreadNotifications->markAsRead();

        return view('dashboard', compact('pendingReferrals', 'notices', 'notifications', 'registeredEventIds'));
    }
}
