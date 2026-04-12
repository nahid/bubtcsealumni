<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagSubscriptionController extends Controller
{
    /**
     * Toggle tag subscription for the authenticated user.
     */
    public function toggle(Request $request, Tag $tag): JsonResponse
    {
        $user = $request->user();

        if ($user->subscribedTags()->where('tags.id', $tag->id)->exists()) {
            $user->subscribedTags()->detach($tag);

            return response()->json([
                'subscribed' => false,
                'message' => "Unfollowed #{$tag->name}",
            ]);
        }

        $user->subscribedTags()->attach($tag, ['subscribed_at' => now()]);

        return response()->json([
            'subscribed' => true,
            'message' => "Following #{$tag->name}",
        ]);
    }
}
