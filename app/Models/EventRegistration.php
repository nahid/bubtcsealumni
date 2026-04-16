<?php

namespace App\Models;

use Database\Factories\EventRegistrationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['notice_id', 'user_id', 'form_data'])]
class EventRegistration extends Model
{
    /** @use HasFactory<EventRegistrationFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'form_data' => 'array',
        ];
    }

    /**
     * The event this registration belongs to.
     */
    public function notice(): BelongsTo
    {
        return $this->belongsTo(Notice::class);
    }

    /**
     * The user who registered.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
