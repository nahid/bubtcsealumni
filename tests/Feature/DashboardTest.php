<?php

use App\Models\Notice;
use App\Models\User;

test('authenticated verified user can view dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful();
});

test('guest cannot view dashboard', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('dashboard displays published notices', function () {
    $user = User::factory()->create();
    $published = Notice::factory()->count(3)->create(['is_published' => true]);
    Notice::factory()->draft()->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertSuccessful();

    foreach ($published as $notice) {
        $response->assertSeeText($notice->title);
    }
});

test('dashboard displays pending referrals for the referrer', function () {
    $referrer = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer->email)->create();

    $this->actingAs($referrer)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSeeText($pendingUser->name);
});

test('dashboard does not display referrals for other users', function () {
    $user = User::factory()->create();
    $otherReferrer = User::factory()->create();
    $pendingUser = User::factory()->withReference($otherReferrer->email)->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertDontSeeText($pendingUser->name);
});
