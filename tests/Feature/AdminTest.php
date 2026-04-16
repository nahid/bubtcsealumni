<?php

use App\Enums\BoardPosition;
use App\Enums\UserRole;
use App\Models\JobPost;
use App\Models\User;

// --- Role System ---

test('user has member role by default', function () {
    $user = User::factory()->create();
    expect($user->role)->toBe(UserRole::Member);
    expect($user->isAdmin())->toBeFalse();
    expect($user->isManager())->toBeFalse();
    expect($user->isStaff())->toBeFalse();
});

test('admin factory state sets admin role', function () {
    $admin = User::factory()->admin()->create();
    expect($admin->role)->toBe(UserRole::Admin);
    expect($admin->isAdmin())->toBeTrue();
    expect($admin->isStaff())->toBeTrue();
});

test('manager factory state sets manager role', function () {
    $manager = User::factory()->manager()->create();
    expect($manager->role)->toBe(UserRole::Manager);
    expect($manager->isManager())->toBeTrue();
    expect($manager->isStaff())->toBeTrue();
    expect($manager->isAdmin())->toBeFalse();
});

test('user can have a board position', function () {
    $user = User::factory()->create(['board_position' => BoardPosition::President]);
    expect($user->board_position)->toBe(BoardPosition::President);
    expect($user->board_position->label())->toBe('President');
});

test('blocked user is detected correctly', function () {
    $user = User::factory()->create(['blocked_at' => now()]);
    expect($user->isBlocked())->toBeTrue();

    $normalUser = User::factory()->create();
    expect($normalUser->isBlocked())->toBeFalse();
});

// --- Middleware ---

test('admin middleware blocks non-admin users', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('admin middleware allows admin users', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertSuccessful();
});

test('staff middleware blocks regular members', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('staff middleware allows managers', function () {
    $manager = User::factory()->manager()->create();
    $this->actingAs($manager)
        ->get(route('admin.dashboard'))
        ->assertSuccessful();
});

test('staff middleware allows admins', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful();
});

test('manager cannot access user management', function () {
    $manager = User::factory()->manager()->create();
    $this->actingAs($manager)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

// --- Admin Dashboard ---

test('admin dashboard shows stats', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Total Users');
});

// --- User Management ---

test('admin can view user list', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['name' => 'Test Alumni']);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertSuccessful()
        ->assertSee('Test Alumni');
});

test('admin can search users', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['name' => 'Findable User']);
    User::factory()->create(['name' => 'Other Person']);

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['search' => 'Findable']))
        ->assertSuccessful()
        ->assertSee('Findable User');
});

test('admin can block a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.block', $user))
        ->assertRedirect();

    expect($user->fresh()->isBlocked())->toBeTrue();
});

test('admin cannot block another admin', function () {
    $admin = User::factory()->admin()->create();
    $otherAdmin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.block', $otherAdmin))
        ->assertRedirect();

    expect($otherAdmin->fresh()->isBlocked())->toBeFalse();
});

test('admin can unblock a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['blocked_at' => now()]);

    $this->actingAs($admin)
        ->post(route('admin.users.unblock', $user))
        ->assertRedirect();

    expect($user->fresh()->isBlocked())->toBeFalse();
});

test('admin can verify a pending user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->pending()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.verify', $user))
        ->assertRedirect();

    expect($user->fresh()->status)->toBe('verified');
});

test('admin can change user role', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.users.role', $user), ['role' => 'manager'])
        ->assertRedirect();

    expect($user->fresh()->role)->toBe(UserRole::Manager);
});

test('admin cannot change own role', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.users.role', $admin), ['role' => 'member'])
        ->assertRedirect();

    expect($admin->fresh()->role)->toBe(UserRole::Admin);
});

test('admin can assign board position', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.users.position', $user), ['board_position' => 'president'])
        ->assertRedirect();

    expect($user->fresh()->board_position)->toBe(BoardPosition::President);
});

test('admin can remove board position', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['board_position' => BoardPosition::President]);

    $this->actingAs($admin)
        ->delete(route('admin.users.position.remove', $user))
        ->assertRedirect();

    expect($user->fresh()->board_position)->toBeNull();
});

test('multiple users can hold same board position', function () {
    $user1 = User::factory()->create(['board_position' => BoardPosition::JointSecretary]);
    $user2 = User::factory()->create(['board_position' => BoardPosition::JointSecretary]);

    expect($user1->board_position)->toBe(BoardPosition::JointSecretary);
    expect($user2->board_position)->toBe(BoardPosition::JointSecretary);
});

// --- Job Moderation ---

test('staff can view job moderation list', function () {
    $admin = User::factory()->admin()->create();
    JobPost::factory()->create(['title' => 'Software Engineer']);

    $this->actingAs($admin)
        ->get(route('admin.jobs.index'))
        ->assertSuccessful()
        ->assertSee('Software Engineer');
});

test('manager can view job moderation list', function () {
    $manager = User::factory()->manager()->create();
    JobPost::factory()->create(['title' => 'Designer Role']);

    $this->actingAs($manager)
        ->get(route('admin.jobs.index'))
        ->assertSuccessful()
        ->assertSee('Designer Role');
});

test('staff can approve a job', function () {
    $admin = User::factory()->admin()->create();
    $job = JobPost::factory()->pending()->create();

    $this->actingAs($admin)
        ->post(route('admin.jobs.approve', $job))
        ->assertRedirect();

    expect($job->fresh()->is_approved)->toBeTrue();
});

test('staff can reject a job', function () {
    $admin = User::factory()->admin()->create();
    $job = JobPost::factory()->create(['is_approved' => true]);

    $this->actingAs($admin)
        ->post(route('admin.jobs.reject', $job))
        ->assertRedirect();

    expect($job->fresh()->is_approved)->toBeFalse();
});

test('staff can delete a job', function () {
    $admin = User::factory()->admin()->create();
    $job = JobPost::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.jobs.destroy', $job))
        ->assertRedirect();

    expect(JobPost::find($job->id))->toBeNull();
});

test('public job listing shows only approved jobs', function () {
    $user = User::factory()->create(['status' => 'verified']);
    JobPost::factory()->create(['title' => 'Approved Job', 'is_approved' => true]);
    JobPost::factory()->create(['title' => 'Pending Job', 'is_approved' => false]);

    $this->actingAs($user)
        ->get(route('jobs.index'))
        ->assertSuccessful()
        ->assertSee('Approved Job')
        ->assertDontSee('Pending Job');
});

// --- Blocked User Login ---

test('blocked user cannot log in', function () {
    $user = User::factory()->create([
        'status' => 'verified',
        'blocked_at' => now(),
        'password' => bcrypt('password'),
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect();

    $this->assertGuest();
});

// --- Manager Notices ---

test('manager can create a notice', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->post(route('notices.store'), [
            'title' => 'Manager Notice',
            'body' => 'Created by manager.',
            'type' => 'notice',
            'is_published' => true,
        ])
        ->assertRedirect();
});
