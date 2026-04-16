<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminJobController extends Controller
{
    /**
     * Display all jobs with moderation controls.
     */
    public function index(Request $request): View
    {
        $query = JobPost::with(['user', 'tags'])->latest();

        if ($filter = $request->input('filter')) {
            match ($filter) {
                'pending' => $query->pending(),
                'approved' => $query->approved(),
                default => null,
            };
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $jobPosts = $query->paginate(20)->withQueryString();

        return view('admin.jobs.index', compact('jobPosts'));
    }

    /**
     * Approve a job post.
     */
    public function approve(JobPost $jobPost): RedirectResponse
    {
        $jobPost->update(['is_approved' => true]);

        return back()->with('success', "Job \"{$jobPost->title}\" has been approved.");
    }

    /**
     * Reject (unapprove) a job post.
     */
    public function reject(JobPost $jobPost): RedirectResponse
    {
        $jobPost->update(['is_approved' => false]);

        return back()->with('success', "Job \"{$jobPost->title}\" has been rejected.");
    }

    /**
     * Delete a job post.
     */
    public function destroy(JobPost $jobPost): RedirectResponse
    {
        $jobPost->delete();

        return back()->with('success', 'Job post has been deleted.');
    }
}
