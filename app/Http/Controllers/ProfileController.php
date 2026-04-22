<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
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
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        // Geocode city when it changes and no manual lat/lon provided
        if (
            array_key_exists('current_city', $validated)
            && $validated['current_city']
            && $validated['current_city'] !== $user->current_city
            && empty($validated['latitude'])
            && empty($validated['longitude'])
        ) {
            $coords = $this->geocodeCity($validated['current_city']);
            if ($coords) {
                $validated['latitude'] = $coords['lat'];
                $validated['longitude'] = $coords['lon'];
            }
        }

        $validated['is_looking_for_job'] = $request->boolean('is_looking_for_job');

        $user->update($validated);

        // Sync subscribed tags
        $tagIds = $request->input('subscribed_tags', []);
        $syncData = collect($tagIds)->mapWithKeys(fn ($id) => [(int) $id => ['subscribed_at' => now()]])->toArray();
        $user->subscribedTags()->sync($syncData);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user->only(['name', 'bio', 'whatsapp_number', 'profile_photo', 'alumni_id', 'facebook_url', 'linkedin_url', 'website_url', 'company_name', 'designation', 'company_website', 'current_city', 'latitude', 'longitude']),
            'profile_photo_url' => $user->profile_photo
                ? Storage::disk('public')->url($user->profile_photo)
                : null,
        ]);
    }

    /**
     * Geocode a city name using OpenStreetMap Nominatim API.
     *
     * @return array{lat: float, lon: float}|null
     */
    private function geocodeCity(string $city): ?array
    {
        try {
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->withHeaders(['User-Agent' => config('app.name', 'BUBTAlumni').'/1.0'])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $city,
                    'format' => 'json',
                    'limit' => 1,
                ]);

            if ($response->successful() && $response->collect()->isNotEmpty()) {
                $result = $response->collect()->first();

                return [
                    'lat' => (float) $result['lat'],
                    'lon' => (float) $result['lon'],
                ];
            }
        } catch (\Throwable) {
            // Geocoding failure is non-critical — silently ignore
        }

        return null;
    }
}
