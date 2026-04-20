<?php

use App\Mail\Auth\ReferenceApprovedMail;
use App\Mail\Auth\RegistrationApprovedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('partial approval sends reference approved email to applicant', function () {
    Mail::fake();

    $referrer1 = User::factory()->create();
    $referrer2 = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer1->email, $referrer2->email)->create();

    $this->actingAs($referrer1)
        ->postJson(route('approvals.approve', $pendingUser))
        ->assertSuccessful();

    Mail::assertQueued(ReferenceApprovedMail::class, function (ReferenceApprovedMail $mail) use ($pendingUser, $referrer1) {
        return $mail->hasTo($pendingUser->email)
            && $mail->applicant->is($pendingUser)
            && $mail->approverName === $referrer1->name;
    });

    Mail::assertNotQueued(RegistrationApprovedMail::class);
});

test('full approval sends registration approved email instead of reference approved', function () {
    Mail::fake();

    $referrer1 = User::factory()->create();
    $referrer2 = User::factory()->create();
    $pendingUser = User::factory()->withReference($referrer1->email, $referrer2->email)->create([
        'reference_1_approved_at' => now(),
    ]);

    $this->actingAs($referrer2)
        ->postJson(route('approvals.approve', $pendingUser))
        ->assertSuccessful();

    Mail::assertQueued(RegistrationApprovedMail::class, function (RegistrationApprovedMail $mail) use ($pendingUser) {
        return $mail->hasTo($pendingUser->email);
    });

    Mail::assertNotQueued(ReferenceApprovedMail::class);
});

test('reference approved email has correct subject', function () {
    $applicant = User::factory()->create();
    $mail = new ReferenceApprovedMail($applicant, 'John Doe');

    expect($mail->envelope()->subject)->toBe('One of Your References Has Approved Your Registration!');
});

test('reference approved email contains approver name', function () {
    $applicant = User::factory()->create(['name' => 'Jane Smith']);
    $mail = new ReferenceApprovedMail($applicant, 'John Doe');

    $rendered = $mail->render();

    expect($rendered)->toContain('John Doe')
        ->and($rendered)->toContain('Jane Smith');
});
