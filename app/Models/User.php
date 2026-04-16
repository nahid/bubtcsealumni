<?php

namespace App\Models;

use App\Enums\BoardPosition;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'mobile', 'password',
    'intake', 'shift', 'reference_email_1', 'reference_email_2',
    'status', 'role', 'board_position', 'blocked_at',
    'bio', 'profile_photo', 'whatsapp_number',
    'alumni_id', 'facebook_url', 'linkedin_url', 'website_url',
    'reference_1_approved_at', 'reference_2_approved_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'intake' => 'integer',
            'role' => UserRole::class,
            'board_position' => BoardPosition::class,
            'blocked_at' => 'datetime',
            'reference_1_approved_at' => 'datetime',
            'reference_2_approved_at' => 'datetime',
        ];
    }

    /**
     * Users who listed this user as one of their references (pending approval).
     *
     * @return Builder<User>
     */
    public function pendingReferrals(): Builder
    {
        return User::where('status', 'pending')
            ->where(function (Builder $query): void {
                $query->where('reference_email_1', $this->email)
                    ->orWhere('reference_email_2', $this->email);
            });
    }

    /**
     * Determine which reference position (1 or 2) the given email holds for this user.
     */
    public function referencePosition(string $email): ?int
    {
        if ($this->reference_email_1 === $email) {
            return 1;
        }

        if ($this->reference_email_2 === $email) {
            return 2;
        }

        return null;
    }

    /**
     * Check if both references have approved this user.
     */
    public function isBothReferencesApproved(): bool
    {
        return $this->reference_1_approved_at !== null
            && $this->reference_2_approved_at !== null;
    }

    /**
     * Job posts created by this user.
     */
    public function jobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class);
    }

    /**
     * Tags this user subscribes to.
     */
    public function subscribedTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withPivot('subscribed_at');
    }

    /**
     * Notices posted by this admin user.
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Event registrations submitted by this user.
     */
    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Check if the user account is verified.
     */
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Check if the user is a manager.
     */
    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }

    /**
     * Check if the user is staff (admin or manager).
     */
    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }

    /**
     * Check if the user account is blocked.
     */
    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    /**
     * Generate the alumni ID from intake, shift, and record ID.
     */
    public static function generateAlumniId(int $intake, string $shift, int $id): string
    {
        $shiftCode = $shift === 'evening' ? 'E' : 'D';

        $userId = str_pad($id, 6, '0', STR_PAD_LEFT);

        return sprintf('BCA-%03d-%s-%06d', $intake, $shiftCode, $userId);
    }

    /**
     * Boot the model and auto-assign alumni ID on creation.
     */
    protected static function booted(): void
    {
        static::created(function (User $user): void {
            if ($user->alumni_id === null) {
                $user->updateQuietly([
                    'alumni_id' => static::generateAlumniId($user->intake, $user->shift, $user->id),
                ]);
            }
        });
    }
}
