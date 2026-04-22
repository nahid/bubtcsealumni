<?php

use App\Models\Tag;
use App\Models\User;

test('members cannot access admin tag management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.tags.index'))
        ->assertForbidden();
});

test('admin can view tag management list', function () {
    $admin = User::factory()->admin()->create();
    Tag::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.tags.index'))
        ->assertSuccessful();
});

test('manager can view tag management list', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->get(route('admin.tags.index'))
        ->assertSuccessful();
});

test('staff can create a tag with auto-generated slug', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.tags.store'), [
            'name' => 'Laravel Framework',
        ])
        ->assertRedirect(route('admin.tags.index'));

    $this->assertDatabaseHas('tags', [
        'name' => 'Laravel Framework',
        'slug' => 'laravel-framework',
    ]);
});

test('staff can create a tag with custom slug', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.tags.store'), [
            'name' => 'PHP',
            'slug' => 'php-lang',
        ])
        ->assertRedirect(route('admin.tags.index'));

    $this->assertDatabaseHas('tags', ['name' => 'PHP', 'slug' => 'php-lang']);
});

test('tag name must be unique', function () {
    $admin = User::factory()->admin()->create();
    Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);

    $this->actingAs($admin)
        ->post(route('admin.tags.store'), ['name' => 'PHP'])
        ->assertSessionHasErrors('name');
});

test('tag slug must be unique', function () {
    $admin = User::factory()->admin()->create();
    Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);

    $this->actingAs($admin)
        ->post(route('admin.tags.store'), ['name' => 'PHP Lang', 'slug' => 'php'])
        ->assertSessionHasErrors('slug');
});

test('staff can update a tag', function () {
    $admin = User::factory()->admin()->create();
    $tag = Tag::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

    $this->actingAs($admin)
        ->put(route('admin.tags.update', $tag), [
            'name' => 'New Name',
            'slug' => 'new-name',
        ])
        ->assertRedirect(route('admin.tags.index'));

    expect($tag->fresh())
        ->name->toBe('New Name')
        ->slug->toBe('new-name');
});

test('updating a tag allows keeping the same name and slug', function () {
    $admin = User::factory()->admin()->create();
    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);

    $this->actingAs($admin)
        ->put(route('admin.tags.update', $tag), [
            'name' => 'PHP',
            'slug' => 'php',
        ])
        ->assertRedirect(route('admin.tags.index'));
});

test('staff can delete a tag', function () {
    $admin = User::factory()->admin()->create();
    $tag = Tag::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.tags.destroy', $tag))
        ->assertRedirect();

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});

test('members cannot create tags', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.tags.store'), ['name' => 'Blocked'])
        ->assertForbidden();

    $this->assertDatabaseMissing('tags', ['name' => 'Blocked']);
});
