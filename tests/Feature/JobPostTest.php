<?php

use App\Models\JobPost;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\NewJobMatchesTagNotification;
use Illuminate\Support\Facades\Notification;

test('authenticated user can view job listings', function () {
    $user = User::factory()->create();
    $jobPost = JobPost::factory()->create();

    $this->actingAs($user)
        ->get(route('jobs.index'))
        ->assertSuccessful()
        ->assertSeeText($jobPost->title);
});

test('guest cannot view job listings', function () {
    $this->get(route('jobs.index'))
        ->assertRedirect(route('login'));
});

test('job listings exclude expired jobs', function () {
    $user = User::factory()->create();
    $activeJob = JobPost::factory()->create();
    $expiredJob = JobPost::factory()->expired()->create();

    $this->actingAs($user)
        ->get(route('jobs.index'))
        ->assertSeeText($activeJob->title)
        ->assertDontSeeText($expiredJob->title);
});

test('job listings exclude closed jobs', function () {
    $user = User::factory()->create();
    $openJob = JobPost::factory()->create();
    $closedJob = JobPost::factory()->closed()->create();

    $this->actingAs($user)
        ->get(route('jobs.index'))
        ->assertSeeText($openJob->title)
        ->assertDontSeeText($closedJob->title);
});

test('job listings can be searched by title', function () {
    $user = User::factory()->create();
    $matchingJob = JobPost::factory()->create(['title' => 'Laravel Developer']);
    $otherJob = JobPost::factory()->create(['title' => 'Graphic Designer']);

    $this->actingAs($user)
        ->get(route('jobs.index', ['search' => 'Laravel']))
        ->assertSeeText('Laravel Developer')
        ->assertDontSeeText('Graphic Designer');
});

test('job listings can be filtered by tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    $taggedJob = JobPost::factory()->create();
    $taggedJob->tags()->attach($tag);
    $untaggedJob = JobPost::factory()->create();

    $this->actingAs($user)
        ->get(route('jobs.index', ['tag' => 'php']))
        ->assertSeeText($taggedJob->title)
        ->assertDontSeeText($untaggedJob->title);
});

test('authenticated user can view job creation form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('jobs.create'))
        ->assertSuccessful();
});

test('authenticated user can create a job post', function () {
    Notification::fake();

    $user = User::factory()->create();
    $tags = Tag::factory()->count(3)->create();

    $this->actingAs($user)
        ->post(route('jobs.store'), [
            'title' => 'Senior Laravel Developer',
            'external_link' => 'https://example.com/jobs/1',
            'salary' => '100k-150k BDT',
            'expiry_date' => now()->addMonth()->format('Y-m-d'),
            'tags' => $tags->pluck('id')->toArray(),
        ])->assertRedirect();

    $this->assertDatabaseHas('job_posts', [
        'title' => 'Senior Laravel Developer',
        'user_id' => $user->id,
        'status' => 'open',
    ]);

    $jobPost = JobPost::where('title', 'Senior Laravel Developer')->first();
    expect($jobPost->tags()->count())->toBe(3);
});

test('creating a job post notifies tag subscribers', function () {
    Notification::fake();

    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    $subscriber = User::factory()->create(['is_looking_for_job' => true]);
    $subscriber->subscribedTags()->attach($tag, ['subscribed_at' => now()]);

    $poster = User::factory()->create();

    $this->actingAs($poster)
        ->post(route('jobs.store'), [
            'title' => 'PHP Developer',
            'external_link' => 'https://example.com/jobs/2',
            'salary' => '80k BDT',
            'expiry_date' => now()->addMonth()->format('Y-m-d'),
            'tags' => [$tag->id],
        ]);

    Notification::assertSentTo($subscriber, NewJobMatchesTagNotification::class);
});

test('subscribers with is_looking_for_job disabled are not notified', function () {
    Notification::fake();

    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    $subscriber = User::factory()->create(['is_looking_for_job' => false]);
    $subscriber->subscribedTags()->attach($tag, ['subscribed_at' => now()]);

    $poster = User::factory()->create();

    $this->actingAs($poster)
        ->post(route('jobs.store'), [
            'title' => 'PHP Developer',
            'external_link' => 'https://example.com/jobs/4',
            'salary' => '80k BDT',
            'expiry_date' => now()->addMonth()->format('Y-m-d'),
            'tags' => [$tag->id],
        ]);

    Notification::assertNotSentTo($subscriber, NewJobMatchesTagNotification::class);
});

test('job poster is not notified about their own job', function () {
    Notification::fake();

    $tag = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    $poster = User::factory()->create();
    $poster->subscribedTags()->attach($tag, ['subscribed_at' => now()]);

    $this->actingAs($poster)
        ->post(route('jobs.store'), [
            'title' => 'PHP Developer',
            'external_link' => 'https://example.com/jobs/3',
            'salary' => '80k BDT',
            'expiry_date' => now()->addMonth()->format('Y-m-d'),
            'tags' => [$tag->id],
        ]);

    Notification::assertNotSentTo($poster, NewJobMatchesTagNotification::class);
});

test('job post creation requires valid data', function (string $field, mixed $value) {
    $user = User::factory()->create();
    $tags = Tag::factory()->count(2)->create();

    $data = [
        'title' => 'Valid Title',
        'external_link' => 'https://example.com',
        'salary' => '80k BDT',
        'expiry_date' => now()->addMonth()->format('Y-m-d'),
        'tags' => $tags->pluck('id')->toArray(),
    ];

    $data[$field] = $value;

    $this->actingAs($user)
        ->post(route('jobs.store'), $data)
        ->assertSessionHasErrors($field);
})->with([
    'title is required' => ['title', ''],
    'external_link is required' => ['external_link', ''],
    'external_link must be a url' => ['external_link', 'not-a-url'],
    'expiry_date is required' => ['expiry_date', ''],
    'expiry_date must be after today' => ['expiry_date', now()->subDay()->format('Y-m-d')],
    'tags are required' => ['tags', []],
]);

test('authenticated user can view a single job post', function () {
    $user = User::factory()->create();
    $jobPost = JobPost::factory()->create();

    $this->actingAs($user)
        ->get(route('jobs.show', $jobPost))
        ->assertSuccessful()
        ->assertSeeText($jobPost->title);
});
