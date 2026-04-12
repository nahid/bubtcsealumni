<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'mobile', 'password',
    'intake', 'shift', 'reference_email_1', 'reference_email_2',
    'status', 'bio', 'profile_photo', 'whatsapp_number',
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
            'is_admin' => 'boolean',
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
        return $this->is_admin === true;
    }
}
