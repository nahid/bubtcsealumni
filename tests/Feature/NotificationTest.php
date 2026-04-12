<?php

use App\Models\JobPost;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\NewJobMatchesTagNotification;
use Illuminate\Support\Facades\Notification;

test('notification contains correct job post data', function () {
    $jobPost = JobPost::factory()->create(['title' => 'Test Job']);
    $notification = new NewJobMatchesTagNotification($jobPost, 'php');

    $user = User::factory()->create();
    $array = $notification->toArray($user);

    expect($array)->toMatchArray([
        'job_post_id' => $jobPost->id,
        'title' => 'Test Job',
        'matched_tag' => 'php',
    ]);
});

test('notification is sent via database and mail channels', function () {
    $jobPost = JobPost::factory()->create();
    $notification = new NewJobMatchesTagNotification($jobPost, 'php');

    $user = User::factory()->create();

    expect($notification->via($user))->toBe(['database', 'mail']);
});

test('notification mail contains job details', function () {
    $jobPost = JobPost::factory()->create(['title' => 'Senior Developer']);
    $notification = new NewJobMatchesTagNotification($jobPost, 'laravel');

    $user = User::factory()->create(['name' => 'John Doe']);
    $mail = $notification->toMail($user);

    expect($mail->subject)->toContain('laravel')
        ->and($mail->greeting)->toContain('John Doe');
});

test('subscriber receives notification for each matching tag only once', function () {
    Notification::fake();

    $tag1 = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
    $tag2 = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

    $subscriber = User::factory()->create();
    $subscriber->subscribedTags()->attach([$tag1->id, $tag2->id], ['subscribed_at' => now()]);

    $poster = User::factory()->create();

    $this->actingAs($poster)
        ->post(route('jobs.store'), [
            'title' => 'Full Stack Developer',
            'external_link' => 'https://example.com/jobs/5',
            'salary' => '100k BDT',
            'expiry_date' => now()->addMonth()->format('Y-m-d'),
            'tags' => 'PHP, Laravel',
        ]);

    // Subscriber should be notified only once (for the first matching tag)
    Notification::assertSentToTimes($subscriber, NewJobMatchesTagNotification::class, 1);
});
