<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\JobPost;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with stats.
     */
    public function __invoke(): View
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('status', 'verified')->count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'blocked_users' => User::whereNotNull('blocked_at')->count(),
            'total_notices' => Notice::where('type', 'notice')->count(),
            'total_events' => Notice::where('type', 'event')->count(),
            'total_registrations' => EventRegistration::count(),
            'total_jobs' => JobPost::count(),
            'pending_jobs' => JobPost::pending()->count(),
            'approved_jobs' => JobPost::approved()->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $pendingJobs = JobPost::with('user')->pending()->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'pendingJobs'));
    }
}
