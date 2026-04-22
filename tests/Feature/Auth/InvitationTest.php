<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

function inviteUser(array $overrides = []): User
{
    return User::factory()->create(array_merge([
        'status' => 'verified',
        'email_verified_at' => null,
        'password' => Hash::make(Str::random(64)),
    ], $overrides));
}

test('valid invitation token shows the set-password form', function () {
    $user = inviteUser();
    $token = Password::broker()->createToken($user);

    $this->get(route('invitation.show', ['token' => $token, 'email' => $user->email]))
        ->assertSuccessful()
        ->assertSee($user->name)
        ->assertSee($user->email);
});

test('invalid invitation token redirects to login with an error', function () {
    $user = inviteUser();

    $this->get(route('invitation.show', ['token' => 'not-a-real-token', 'email' => $user->email]))
        ->assertRedirect(route('login'))
        ->assertSessionHas('error');
});

test('invitation page for unknown email redirects to login', function () {
    $this->get(route('invitation.show', ['token' => 'x', 'email' => 'ghost@example.com']))
        ->assertRedirect(route('login'))
        ->assertSessionHas('error');
});

test('user can set password via a valid invitation', function () {
    $user = inviteUser(['email' => 'invitee@example.com']);
    $token = Password::broker()->createToken($user);

    $this->post(route('invitation.store', ['token' => $token]), [
        'email' => 'invitee@example.com',
        'password' => 'MyStr0ngPass!',
        'password_confirmation' => 'MyStr0ngPass!',
    ])->assertRedirect(route('dashboard'));

    $fresh = $user->fresh();

    expect(Hash::check('MyStr0ngPass!', $fresh->password))->toBeTrue()
        ->and($fresh->email_verified_at)->not->toBeNull();

    $this->assertAuthenticatedAs($fresh);
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'invitee@example.com']);
});

test('set password fails with mismatched confirmation', function () {
    $user = inviteUser();
    $token = Password::broker()->createToken($user);

    $this->post(route('invitation.store', ['token' => $token]), [
        'email' => $user->email,
        'password' => 'MyStr0ngPass!',
        'password_confirmation' => 'Different!',
    ])->assertSessionHasErrors('password');

    $this->assertGuest();
});

test('set password fails with invalid token', function () {
    $user = inviteUser();

    $this->post(route('invitation.store', ['token' => 'bogus']), [
        'email' => $user->email,
        'password' => 'MyStr0ngPass!',
        'password_confirmation' => 'MyStr0ngPass!',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
    expect($user->fresh()->email_verified_at)->toBeNull();
});

test('invitation routes are guest-only', function () {
    $existing = User::factory()->create();
    $user = inviteUser();
    $token = Password::broker()->createToken($user);

    $this->actingAs($existing)
        ->get(route('invitation.show', ['token' => $token, 'email' => $user->email]))
        ->assertRedirect();
});
