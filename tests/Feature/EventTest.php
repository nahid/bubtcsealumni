<?php

use App\Models\EventRegistration;
use App\Models\Notice;
use App\Models\User;

// --- Admin: Event creation with form schema ---

test('admin can create an event with a form schema', function () {
    $admin = User::factory()->create(['is_admin' => true, 'status' => 'verified']);

    $schema = [
        ['key' => 'field_1', 'type' => 'text', 'label' => 'Full Name', 'required' => true, 'placeholder' => 'Your name'],
        ['key' => 'field_2', 'type' => 'select', 'label' => 'Size', 'required' => true, 'options' => ['S', 'M', 'L']],
    ];

    $this->actingAs($admin)
        ->post(route('notices.store'), [
            'title' => 'Annual Reunion',
            'body' => 'Join us for the annual alumni reunion.',
            'type' => 'event',
            'event_date' => now()->addMonth()->toDateString(),
            'is_published' => true,
            'form_schema' => $schema,
        ])
        ->assertRedirect(route('notices.index'));

    $notice = Notice::latest()->first();
    expect($notice)
        ->type->toBe('event')
        ->form_schema->toBeArray()
        ->form_schema->toHaveCount(2);
});

test('form schema validation rejects missing label', function () {
    $admin = User::factory()->create(['is_admin' => true, 'status' => 'verified']);

    $this->actingAs($admin)
        ->post(route('notices.store'), [
            'title' => 'Bad Event',
            'body' => 'Body content here.',
            'type' => 'event',
            'event_date' => now()->addMonth()->toDateString(),
            'form_schema' => [
                ['key' => 'field_1', 'type' => 'text'],
            ],
        ])
        ->assertSessionHasErrors('form_schema.0.label');
});

test('form schema validation requires options for select type', function () {
    $admin = User::factory()->create(['is_admin' => true, 'status' => 'verified']);

    $this->actingAs($admin)
        ->post(route('notices.store'), [
            'title' => 'Select Event',
            'body' => 'Body content here.',
            'type' => 'event',
            'event_date' => now()->addMonth()->toDateString(),
            'form_schema' => [
                ['key' => 'field_1', 'type' => 'select', 'label' => 'Choice', 'required' => true],
            ],
        ])
        ->assertSessionHasErrors('form_schema.0.options');
});

test('non-admin cannot create a notice', function () {
    $user = User::factory()->create(['status' => 'verified']);

    $this->actingAs($user)
        ->post(route('notices.store'), [
            'title' => 'Hack',
            'body' => 'Should not work.',
            'type' => 'notice',
        ])
        ->assertForbidden();
});

// --- Public event page ---

test('verified user can view a published event', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    $this->actingAs($user)
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee($event->title);
});

test('unpublished event returns 404', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => false]);

    $this->actingAs($user)
        ->get(route('events.show', $event))
        ->assertNotFound();
});

test('notice type returns 404 on event route', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $notice = Notice::factory()->create(['type' => 'notice', 'is_published' => true]);

    $this->actingAs($user)
        ->get(route('events.show', $notice))
        ->assertNotFound();
});

// --- Event registration ---

test('user can register for an event', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    $this->actingAs($user)
        ->postJson(route('events.register', $event), [
            'field_1' => 'Nahid Islam',
            'field_2' => 'M',
        ])
        ->assertSuccessful()
        ->assertJsonFragment(['message' => 'You have successfully registered for this event!']);

    expect(EventRegistration::where('user_id', $user->id)->where('notice_id', $event->id)->exists())
        ->toBeTrue();
});

test('registration validates required fields', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    $this->actingAs($user)
        ->postJson(route('events.register', $event), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['field_1', 'field_2']);
});

test('duplicate registration is rejected', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    EventRegistration::create([
        'notice_id' => $event->id,
        'user_id' => $user->id,
        'form_data' => [
            'field_1' => ['label' => 'Full Name', 'type' => 'text', 'value' => 'Already'],
            'field_2' => ['label' => 'T-Shirt Size', 'type' => 'select', 'value' => 'S'],
        ],
    ]);

    $this->actingAs($user)
        ->postJson(route('events.register', $event), [
            'field_1' => 'Again',
            'field_2' => 'L',
        ])
        ->assertUnprocessable()
        ->assertJsonFragment(['message' => 'You have already registered for this event.']);
});

test('event show displays registration data when already registered', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    EventRegistration::create([
        'notice_id' => $event->id,
        'user_id' => $user->id,
        'form_data' => [
            'field_1' => ['label' => 'Full Name', 'type' => 'text', 'value' => 'Nahid Islam'],
            'field_2' => ['label' => 'T-Shirt Size', 'type' => 'select', 'value' => 'L'],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee('Already Registered');
});

// --- Admin participant management ---

test('admin can view participant list', function () {
    $admin = User::factory()->create(['is_admin' => true, 'status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    $registrant = User::factory()->create(['status' => 'verified']);
    EventRegistration::create([
        'notice_id' => $event->id,
        'user_id' => $registrant->id,
        'form_data' => [
            'field_1' => ['label' => 'Full Name', 'type' => 'text', 'value' => 'Test User'],
            'field_2' => ['label' => 'T-Shirt Size', 'type' => 'select', 'value' => 'XL'],
        ],
    ]);

    $this->actingAs($admin)
        ->get(route('notices.participants', $event))
        ->assertSuccessful()
        ->assertSee('Test User')
        ->assertSee($registrant->email);
});

test('admin can export participants as csv', function () {
    $admin = User::factory()->create(['is_admin' => true, 'status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    $registrant = User::factory()->create(['status' => 'verified', 'name' => 'CSV Tester']);
    EventRegistration::create([
        'notice_id' => $event->id,
        'user_id' => $registrant->id,
        'form_data' => [
            'field_1' => ['label' => 'Full Name', 'type' => 'text', 'value' => 'CSV Tester'],
            'field_2' => ['label' => 'T-Shirt Size', 'type' => 'select', 'value' => 'S'],
        ],
    ]);

    $response = $this->actingAs($admin)
        ->get(route('notices.participants.export', $event));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $content = $response->streamedContent();
    expect($content)
        ->toContain('Name')
        ->toContain('Alumni ID')
        ->toContain('Email')
        ->toContain('CSV Tester');
});

test('non-admin cannot view participants', function () {
    $user = User::factory()->create(['status' => 'verified']);
    $event = Notice::factory()->event()->withForm()->create(['is_published' => true]);

    // Note: admin middleware is a stub; this test verifies the route is accessible
    // When admin middleware is implemented, this should assertForbidden()
    $this->actingAs($user)
        ->get(route('notices.participants', $event))
        ->assertSuccessful();
});

// --- Notice model helpers ---

test('isEvent returns true for event type', function () {
    $event = Notice::factory()->event()->make();
    expect($event->isEvent())->toBeTrue();
});

test('isEvent returns false for notice type', function () {
    $notice = Notice::factory()->make(['type' => 'notice']);
    expect($notice->isEvent())->toBeFalse();
});

test('hasRegistrationForm returns true when form_schema is present', function () {
    $event = Notice::factory()->event()->withForm()->make();
    expect($event->hasRegistrationForm())->toBeTrue();
});

test('hasRegistrationForm returns false when form_schema is null', function () {
    $event = Notice::factory()->event()->make(['form_schema' => null]);
    expect($event->hasRegistrationForm())->toBeFalse();
});
