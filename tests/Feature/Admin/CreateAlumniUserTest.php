<?php

use App\Mail\Auth\AlumniInvitationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('admin can view the create alumni user page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertSuccessful()
        ->assertSee('Create Alumni User');
});

test('non-admin cannot access the create alumni user page', function () {
    $user = User::factory()->create(['status' => 'verified']);

    $this->actingAs($user)
        ->get(route('admin.users.create'))
        ->assertForbidden();
});

test('admin can create a new alumni user and send an invitation email', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Jane Alumni',
            'email' => 'jane@example.com',
            'mobile' => '01712345678',
            'intake' => 10,
            'shift' => 'day',
            'role' => 'member',
        ])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    $user = User::where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->status)->toBe('verified')
        ->and($user->email_verified_at)->toBeNull()
        ->and($user->alumni_id)->not->toBeNull();

    Mail::assertQueued(AlumniInvitationMail::class, fn ($mail) => $mail->hasTo('jane@example.com'));
});

test('store creates a password reset token for the new user', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Token Test',
            'email' => 'token@example.com',
            'mobile' => '01712345678',
            'intake' => 10,
            'shift' => 'day',
            'role' => 'member',
        ])->assertRedirect();

    $this->assertDatabaseHas('password_reset_tokens', ['email' => 'token@example.com']);
});

test('store rejects duplicate email', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Duplicate',
            'email' => 'taken@example.com',
            'mobile' => '01712345678',
            'intake' => 10,
            'shift' => 'day',
            'role' => 'member',
        ])
        ->assertSessionHasErrors('email');
});

test('store rejects invalid mobile number', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Bad Mobile',
            'email' => 'bad@example.com',
            'mobile' => '12345',
            'intake' => 10,
            'shift' => 'day',
            'role' => 'member',
        ])
        ->assertSessionHasErrors('mobile');
});

test('store rejects invalid shift', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Bad Shift',
            'email' => 'bad2@example.com',
            'mobile' => '01712345678',
            'intake' => 10,
            'shift' => 'night',
            'role' => 'member',
        ])
        ->assertSessionHasErrors('shift');
});

test('store rejects invalid role', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Bad Role',
            'email' => 'bad3@example.com',
            'mobile' => '01712345678',
            'intake' => 10,
            'shift' => 'day',
            'role' => 'superuser',
        ])
        ->assertSessionHasErrors('role');
});

test('non-admin cannot create alumni user', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->post(route('admin.users.store'), [
            'name' => 'X',
            'email' => 'x@example.com',
            'mobile' => '01712345678',
            'intake' => 10,
            'shift' => 'day',
            'role' => 'member',
        ])
        ->assertForbidden();
});
