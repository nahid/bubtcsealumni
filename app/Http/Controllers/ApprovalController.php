<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Approve a pending referral.
     */
    public function approve(Request $request, User $user): JsonResponse
    {
        $this->ensureIsReferrer($request, $user);

        $user->update(['status' => 'verified']);

        return response()->json(['message' => "{$user->name} has been verified."]);
    }

    /**
     * Reject (delete) a pending referral.
     */
    public function reject(Request $request, User $user): JsonResponse
    {
        $this->ensureIsReferrer($request, $user);

        $user->delete();

        return response()->json(['message' => 'Registration has been declined.']);
    }

    /**
     * Ensure the authenticated user is the referrer of the given pending user.
     */
    private function ensureIsReferrer(Request $request, User $user): void
    {
        abort_unless(
            $user->status === 'pending' && $user->reference_email === $request->user()->email,
            403,
            'You are not authorized to perform this action.'
        );
    }
}
