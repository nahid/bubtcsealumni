<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BoardPosition;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\Auth\AccountBlockedMail;
use App\Mail\Auth\AccountUnblockedMail;
use App\Mail\Auth\AccountVerifiedMail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display user list with search and filters.
     */
    public function index(Request $request): View
    {
        $query = User::query()->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('alumni_id', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            if ($status === 'blocked') {
                $query->whereNotNull('blocked_at');
            } else {
                $query->where('status', $status)->whereNull('blocked_at');
            }
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = UserRole::cases();
        $positions = BoardPosition::cases();

        return view('admin.users.index', compact('users', 'roles', 'positions'));
    }

    /**
     * Block a user account.
     */
    public function block(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot block an admin user.');
        }

        $user->update(['blocked_at' => now()]);

        Mail::to($user->email)->send(new AccountBlockedMail($user));

        return back()->with('success', "{$user->name} has been blocked.");
    }

    /**
     * Unblock a user account.
     */
    public function unblock(User $user): RedirectResponse
    {
        $user->update(['blocked_at' => null]);

        Mail::to($user->email)->send(new AccountUnblockedMail($user));

        return back()->with('success', "{$user->name} has been unblocked.");
    }

    /**
     * Verify a pending user account.
     */
    public function verify(User $user): RedirectResponse
    {
        $user->update(['status' => 'verified']);

        Mail::to($user->email)->send(new AccountVerifiedMail($user));

        return back()->with('success', "{$user->name} has been verified.");
    }

    /**
     * Change a user's role.
     */
    public function changeRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "{$user->name}'s role updated to {$validated['role']}.");
    }

    /**
     * Assign a board position to a user.
     */
    public function assignPosition(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'board_position' => ['required', Rule::enum(BoardPosition::class)],
        ]);

        $user->update(['board_position' => $validated['board_position']]);

        $position = BoardPosition::from($validated['board_position']);

        return back()->with('success', "{$user->name} assigned as {$position->label()}.");
    }

    /**
     * Remove a user's board position.
     */
    public function removePosition(User $user): RedirectResponse
    {
        $user->update(['board_position' => null]);

        return back()->with('success', "{$user->name}'s board position has been removed.");
    }
}
