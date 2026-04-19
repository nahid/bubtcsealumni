<?php

namespace App\Http\Controllers;

use App\Mail\Auth\RegistrationApprovedMail;
use App\Mail\Auth\RegistrationRejectedMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    /**
     * Approve a pending referral.
     */
    public function approve(Request $request, User $user): JsonResponse
    {
        $position = $this->authorizedReferencePosition($request, $user);

        $user->update([
            "reference_{$position}_approved_at" => now(),
        ]);

        if ($user->fresh()->isBothReferencesApproved()) {
            $user->update(['status' => 'verified']);

            Mail::to($user->email)->send(new RegistrationApprovedMail($user));

            return response()->json(['message' => "{$user->name} has been fully verified!"]);
        }

        return response()->json(['message' => "You approved {$user->name}. Waiting for the other reference."]);
    }

    /**
     * Reject (delete) a pending referral.
     */
    public function reject(Request $request, User $user): JsonResponse
    {
        $this->authorizedReferencePosition($request, $user);

        Mail::to($user->email)->send(new RegistrationRejectedMail($user));

        $user->delete();

        return response()->json(['message' => 'Registration has been declined.']);
    }

    /**
     * Ensure the authenticated user is a referrer and return their position (1 or 2).
     */
    private function authorizedReferencePosition(Request $request, User $user): int
    {
        abort_unless($user->status === 'pending', 403, 'This user is not pending approval.');

        $position = $user->referencePosition($request->user()->email);

        abort_unless($position !== null, 403, 'You are not authorized to perform this action.');

        return $position;
    }
}
