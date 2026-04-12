<?php

use App\Models\User;

test('referrer can approve a pending user', function () {
    $referrer = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer->email)->create();

    $this->actingAs($referrer)
        ->postJson(route('approvals.approve', $pendingUser))
        ->assertSuccessful()
        ->assertJsonFragment(['message' => "{$pendingUser->name} has been verified."]);

    expect($pendingUser->fresh()->status)->toBe('verified');
});

test('referrer can reject a pending user', function () {
    $referrer = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer->email)->create();

    $this->actingAs($referrer)
        ->postJson(route('approvals.reject', $pendingUser))
        ->assertSuccessful()
        ->assertJsonFragment(['message' => 'Registration has been declined.']);

    expect(User::find($pendingUser->id))->toBeNull();
});

test('non-referrer cannot approve a pending user', function () {
    $otherUser = User::factory()->create();
    $referrer = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer->email)->create();

    $this->actingAs($otherUser)
        ->postJson(route('approvals.approve', $pendingUser))
        ->assertForbidden();

    expect($pendingUser->fresh()->status)->toBe('pending');
});

test('non-referrer cannot reject a pending user', function () {
    $otherUser = User::factory()->create();
    $referrer = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer->email)->create();

    $this->actingAs($otherUser)
        ->postJson(route('approvals.reject', $pendingUser))
        ->assertForbidden();

    expect(User::find($pendingUser->id))->not->toBeNull();
});

test('cannot approve an already verified user', function () {
    $referrer = User::factory()->create();
    $verifiedUser = User::factory()->create(['reference_email' => $referrer->email]);

    $this->actingAs($referrer)
        ->postJson(route('approvals.approve', $verifiedUser))
        ->assertForbidden();
});

test('guest cannot approve a user', function () {
    $pendingUser = User::factory()->pending()->create();

    $this->postJson(route('approvals.approve', $pendingUser))
        ->assertUnauthorized();
});
