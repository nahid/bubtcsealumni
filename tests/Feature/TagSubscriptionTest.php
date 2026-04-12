<?php

use App\Models\Tag;
use App\Models\User;

test('authenticated user can subscribe to a tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();

    $this->actingAs($user)
        ->postJson(route('tags.toggle', $tag))
        ->assertSuccessful()
        ->assertJson([
            'subscribed' => true,
            'message' => "Following #{$tag->name}",
        ]);

    expect($user->subscribedTags()->where('tags.id', $tag->id)->exists())->toBeTrue();
});

test('authenticated user can unsubscribe from a tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();
    $user->subscribedTags()->attach($tag, ['subscribed_at' => now()]);

    $this->actingAs($user)
        ->postJson(route('tags.toggle', $tag))
        ->assertSuccessful()
        ->assertJson([
            'subscribed' => false,
            'message' => "Unfollowed #{$tag->name}",
        ]);

    expect($user->subscribedTags()->where('tags.id', $tag->id)->exists())->toBeFalse();
});

test('guest cannot toggle tag subscription', function () {
    $tag = Tag::factory()->create();

    $this->postJson(route('tags.toggle', $tag))
        ->assertUnauthorized();
});
