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

        return view('dashboard', compact('pendingReferrals', 'notices'));
    }
}
