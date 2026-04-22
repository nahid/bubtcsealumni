<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobPostRequest;
use App\Models\JobPost;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\NewJobMatchesTagNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    /**
     * Display job listings with search and tag filtering.
     */
    public function index(Request $request): View
    {
        $query = JobPost::with(['user', 'tags'])
            ->approved()
            ->where('status', 'open')
            ->where('expiry_date', '>=', now())
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($tagSlug = $request->input('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
        }

        $jobPosts = $query->paginate(12)->withQueryString();
        $allTags = Tag::orderBy('name')->get();

        return view('jobs.index', compact('jobPosts', 'allTags'));
    }

    /**
     * Show the form for creating a new job post.
     */
    public function create(): View
    {
        $existingTags = Tag::orderBy('name')->get();

        return view('jobs.create', compact('existingTags'));
    }

    /**
     * Store a newly created job post.
     */
    public function store(StoreJobPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var JobPost $jobPost */
        $description = $validated['description'] ?? null;
        if ($description !== null) {
            $description = strip_tags($description, '<p><br><strong><em><u><s><a><ul><ol><li><h1><h2><h3><blockquote><code>');
            $description = trim($description) === '' ? null : $description;
        }

        $jobPost = $request->user()->jobPosts()->create([
            'title' => $validated['title'],
            'company_name' => $validated['company_name'],
            'description' => $description,
            'external_link' => $validated['external_link'],
            'salary' => $validated['salary'],
            'expiry_date' => $validated['expiry_date'],
            'status' => 'open',
        ]);

        // Attach selected tags
        $tagIds = $validated['tags'];
        $jobPost->tags()->sync($tagIds);

        // Notify subscribers of matching tags
        $this->notifyTagSubscribers($jobPost, $tagIds);

        return redirect()->route('jobs.show', $jobPost)
            ->with('success', 'Job post created successfully!');
    }

    /**
     * Display a single job post.
     */
    public function show(JobPost $jobPost): View
    {
        $jobPost->load(['user', 'tags']);

        return view('jobs.show', compact('jobPost'));
    }

    /**
     * Notify users subscribed to matching tags about a new job post.
     *
     * @param  array<int>  $tagIds
     */
    private function notifyTagSubscribers(JobPost $jobPost, array $tagIds): void
    {
        $tags = Tag::whereIn('id', $tagIds)->get();

        // Collect unique subscribers across all matching tags
        $notifiedUserIds = [];

        foreach ($tags as $tag) {
            $subscribers = User::whereHas('subscribedTags', fn ($q) => $q->where('tags.id', $tag->id))
                ->where('is_looking_for_job', true)
                ->where('id', '!=', $jobPost->user_id)
                ->whereNotIn('id', $notifiedUserIds)
                ->get();

            foreach ($subscribers as $subscriber) {
                $subscriber->notify(new NewJobMatchesTagNotification($jobPost, $tag->name));
                $notifiedUserIds[] = $subscriber->id;
            }
        }
    }
}
