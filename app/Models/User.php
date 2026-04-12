<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'mobile', 'password',
    'intake', 'shift', 'reference_email',
    'status', 'bio', 'profile_photo', 'whatsapp_number',
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
        ];
    }

    /**
     * Users who listed this user as their reference.
     */
    public function pendingReferrals(): HasMany
    {
        return $this->hasMany(User::class, 'reference_email', 'email')
            ->where('status', 'pending');
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
