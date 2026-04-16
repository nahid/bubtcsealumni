<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a user's public profile.
     */
    public function show(User $user): View
    {
        $user->loadCount('jobPosts');

        return view('profile.show', compact('user'));
    }

    /**
     * Show the profile edit form for the authenticated user.
     */
    public function edit(): View
    {
        $user = auth()->user();
        $allTags = Tag::orderBy('name')->get();
        $subscribedTagIds = $user->subscribedTags()->pluck('tags.id')->toArray();

        return view('profile.edit', compact('user', 'allTags', 'subscribedTagIds'));
    }

    /**
     * Update the authenticated user's profile via AJAX.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        $user->update($validated);

        // Sync subscribed tags
        $tagIds = $request->input('subscribed_tags', []);
        $syncData = collect($tagIds)->mapWithKeys(fn ($id) => [(int) $id => ['subscribed_at' => now()]])->toArray();
        $user->subscribedTags()->sync($syncData);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user->only(['name', 'bio', 'whatsapp_number', 'profile_photo', 'alumni_id', 'facebook_url', 'linkedin_url', 'website_url']),
            'profile_photo_url' => $user->profile_photo
                ? Storage::disk('public')->url($user->profile_photo)
                : null,
        ]);
    }
}
