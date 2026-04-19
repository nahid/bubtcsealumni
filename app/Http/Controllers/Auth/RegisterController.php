<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\Auth\ReferenceApprovalRequestMail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => $validated['password'],
            'intake' => $validated['intake'],
            'shift' => $validated['shift'],
            'reference_email_1' => $validated['reference_email_1'],
            'reference_email_2' => $validated['reference_email_2'],
            'status' => 'pending',
        ]);

        Mail::to($validated['reference_email_1'])->send(new ReferenceApprovalRequestMail($user));
        Mail::to($validated['reference_email_2'])->send(new ReferenceApprovalRequestMail($user));

        return redirect()->route('login')
            ->with('success', 'Registration successful! Your account is pending approval by both references.');
    }
}
