<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

// --- Alumni ID generation ---

test('alumni id is auto-generated when a user is created', function () {
    $user = User::factory()->create(['intake' => 6, 'shift' => 'evening']);

    expect($user->fresh()->alumni_id)
        ->toMatch('/^BCA-006-E-\d{6}$/');
});

test('alumni id uses D for day shift and E for evening shift', function (string $shift, string $code) {
    $user = User::factory()->create(['intake' => 12, 'shift' => $shift]);

    expect($user->fresh()->alumni_id)->toContain("-{$code}-");
})->with([
    'day shift' => ['day', 'D'],
    'evening shift' => ['evening', 'E'],
]);

test('alumni id pads intake to 3 digits and id to 6 digits', function () {
    $user = User::factory()->create(['intake' => 1, 'shift' => 'day']);
    $expected = sprintf('BCA-001-D-%06d', $user->id);

    expect($user->fresh()->alumni_id)->toBe($expected);
});

test('generateAlumniId static helper returns correct format', function () {
    expect(User::generateAlumniId(6, 'evening', 2316))
        ->toBe('BCA-006-E-002316');
});

// --- Profile edit page ---

test('authenticated user can view profile edit page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertSuccessful()
        ->assertSeeText('Edit Profile')
        ->assertSee($user->fresh()->alumni_id);
});

test('guest cannot view profile edit page', function () {
    $this->get(route('profile.edit'))
        ->assertRedirect(route('login'));
});

// --- Social link updates ---

test('user can update social media links', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'facebook_url' => 'https://facebook.com/johndoe',
            'linkedin_url' => 'https://linkedin.com/in/johndoe',
            'website_url' => 'https://johndoe.com',
        ])
        ->assertSuccessful()
        ->assertJsonFragment(['message' => 'Profile updated successfully!']);

    $user->refresh();
    expect($user->facebook_url)->toBe('https://facebook.com/johndoe')
        ->and($user->linkedin_url)->toBe('https://linkedin.com/in/johndoe')
        ->and($user->website_url)->toBe('https://johndoe.com');
});

test('social media links can be cleared', function () {
    $user = User::factory()->create([
        'facebook_url' => 'https://facebook.com/johndoe',
        'linkedin_url' => 'https://linkedin.com/in/johndoe',
        'website_url' => 'https://johndoe.com',
    ]);

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'facebook_url' => null,
            'linkedin_url' => null,
            'website_url' => null,
        ])
        ->assertSuccessful();

    $user->refresh();
    expect($user->facebook_url)->toBeNull()
        ->and($user->linkedin_url)->toBeNull()
        ->and($user->website_url)->toBeNull();
});

test('social media links must be valid urls', function (string $field) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            $field => 'not-a-url',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors($field);
})->with(['facebook_url', 'linkedin_url', 'website_url']);

// --- Public profile ---

test('public profile shows alumni id and social links', function () {
    $user = User::factory()->create([
        'facebook_url' => 'https://facebook.com/johndoe',
        'linkedin_url' => 'https://linkedin.com/in/johndoe',
        'website_url' => 'https://johndoe.com',
    ]);

    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $user))
        ->assertSuccessful()
        ->assertSee($user->fresh()->alumni_id)
        ->assertSee('https://facebook.com/johndoe')
        ->assertSee('https://linkedin.com/in/johndoe')
        ->assertSee('https://johndoe.com');
});

test('public profile hides social links when not set', function () {
    $user = User::factory()->create([
        'facebook_url' => null,
        'linkedin_url' => null,
        'website_url' => null,
    ]);

    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $user))
        ->assertSuccessful()
        ->assertDontSee('Facebook')
        ->assertDontSee('LinkedIn')
        ->assertDontSee('Website');
});

// --- Work Information ---

test('user can update work information', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'company_name' => 'Google',
            'designation' => 'Software Engineer',
            'company_website' => 'https://google.com',
        ])
        ->assertSuccessful();

    $user->refresh();
    expect($user->company_name)->toBe('Google')
        ->and($user->designation)->toBe('Software Engineer')
        ->and($user->company_website)->toBe('https://google.com');
});

test('work information fields are optional', function () {
    $user = User::factory()->create([
        'company_name' => 'Old Corp',
        'designation' => 'Old Title',
    ]);

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'company_name' => null,
            'designation' => null,
            'company_website' => null,
        ])
        ->assertSuccessful();

    $user->refresh();
    expect($user->company_name)->toBeNull()
        ->and($user->designation)->toBeNull()
        ->and($user->company_website)->toBeNull();
});

test('company website must be a valid url', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'company_website' => 'not-a-url',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('company_website');
});

// --- Location ---

test('user can update location information', function () {
    Http::fake();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'current_city' => 'Dhaka',
            'latitude' => 23.8103,
            'longitude' => 90.4125,
        ])
        ->assertSuccessful();

    $user->refresh();
    expect($user->current_city)->toBe('Dhaka')
        ->and((float) $user->latitude)->toBe(23.8103)
        ->and((float) $user->longitude)->toBe(90.4125);
});

test('changing city auto-geocodes lat/lon from openstreetmap when no manual coords given', function () {
    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            ['lat' => '23.8103', 'lon' => '90.4125', 'display_name' => 'Dhaka, Bangladesh'],
        ]),
    ]);

    $user = User::factory()->create([
        'current_city' => null,
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'current_city' => 'Dhaka',
        ])
        ->assertSuccessful();

    $user->refresh();
    expect($user->current_city)->toBe('Dhaka')
        ->and((float) $user->latitude)->toBe(23.8103)
        ->and((float) $user->longitude)->toBe(90.4125);

    Http::assertSent(fn ($request) => str_contains($request->url(), 'nominatim.openstreetmap.org'));
});

test('manual lat/lon takes priority over geocoding', function () {
    Http::fake();

    $user = User::factory()->create(['current_city' => null, 'latitude' => null, 'longitude' => null]);

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'current_city' => 'Dhaka',
            'latitude' => 10.0,
            'longitude' => 20.0,
        ])
        ->assertSuccessful();

    $user->refresh();
    expect((float) $user->latitude)->toBe(10.0)
        ->and((float) $user->longitude)->toBe(20.0);

    Http::assertNothingSent();
});

test('geocoding failure does not break profile update', function () {
    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([], 500),
    ]);

    $user = User::factory()->create([
        'current_city' => null,
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->actingAs($user)
        ->postJson(route('profile.update'), [
            'current_city' => 'UnknownCity',
        ])
        ->assertSuccessful();

    $user->refresh();
    expect($user->current_city)->toBe('UnknownCity')
        ->and($user->latitude)->toBeNull()
        ->and($user->longitude)->toBeNull();
});

test('latitude must be between -90 and 90', function (float $value) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), ['latitude' => $value])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('latitude');
})->with([-91.0, 91.0]);

test('longitude must be between -180 and 180', function (float $value) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('profile.update'), ['longitude' => $value])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('longitude');
})->with([-181.0, 181.0]);

// --- Profile show with work & location ---

test('public profile shows work information', function () {
    $user = User::factory()->create([
        'company_name' => 'Grameenphone',
        'designation' => 'Lead Developer',
        'company_website' => 'https://grameenphone.com',
    ]);

    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $user))
        ->assertSuccessful()
        ->assertSee('Grameenphone')
        ->assertSee('Lead Developer');
});

test('public profile shows location', function () {
    $user = User::factory()->create([
        'current_city' => 'Chittagong',
    ]);

    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $user))
        ->assertSuccessful()
        ->assertSee('Chittagong');
});

// --- Directory map data ---

test('directory page includes map data for alumni with location', function () {
    User::factory()->create([
        'current_city' => 'Dhaka',
        'latitude' => 23.8103,
        'longitude' => 90.4125,
    ]);
    User::factory()->create([
        'latitude' => null,
        'longitude' => null,
    ]);

    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('directory.index'))
        ->assertSuccessful()
        ->assertSee('alumni-map')
        ->assertSee('23.8103');
});

test('directory page shows company info on alumni cards', function () {
    User::factory()->create([
        'company_name' => 'TechCorp',
        'designation' => 'CTO',
    ]);

    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('directory.index'))
        ->assertSuccessful()
        ->assertSee('CTO')
        ->assertSee('TechCorp');
});
