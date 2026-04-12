<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'slug'])]
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    /**
     * Job posts that have this tag.
     */
    public function jobPosts(): BelongsToMany
    {
        return $this->belongsToMany(JobPost::class);
    }

    /**
     * Users who subscribe to this tag.
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('subscribed_at');
    }
}
