<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LandingController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $stats = [
            'members' => User::where('status', 'verified')->count(),
            'intakes' => User::where('status', 'verified')->distinct('intake')->count('intake'),
            'jobs' => JobPost::where('is_approved', true)->count(),
            'cities' => User::where('status', 'verified')->whereNotNull('current_city')->distinct('current_city')->count('current_city'),
        ];

        $recentJobs = JobPost::where('is_approved', true)
            ->with('tags')
            ->latest()
            ->take(3)
            ->get();

        $upcomingEvents = Notice::where('is_published', true)
            ->where('type', 'event')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(2)
            ->get();

        return view('welcome', compact('stats', 'recentJobs', 'upcomingEvents'));
    }
}
