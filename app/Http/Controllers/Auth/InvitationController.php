<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SetPasswordRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class InvitationController extends Controller
{
    /**
     * Show the set-password form for an invitation.
     */
    public function show(Request $request, string $token): View|RedirectResponse
    {
        $email = (string) $request->query('email', '');

        $user = User::where('email', $email)->first();

        if (! $user || ! Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('login')
                ->with('error', 'This invitation link is invalid or has expired.');
        }

        return view('auth.invitation', [
            'user' => $user,
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Accept the invitation: set the password and verify the email.
     */
    public function store(SetPasswordRequest $request, string $token): RedirectResponse
    {
        $status = Password::broker()->reset(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $token,
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'email_verified_at' => now(),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            Auth::login($user);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Welcome! Your account is now active.');
    }
}
