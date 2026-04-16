<?php

namespace App\Models;

use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'body', 'type', 'event_date', 'form_schema', 'is_published'])]
class Notice extends Model
{
    /** @use HasFactory<NoticeFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_published' => 'boolean',
            'form_schema' => 'array',
        ];
    }

    /**
     * The admin who posted this notice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Event registrations for this notice.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Check if this notice is an event.
     */
    public function isEvent(): bool
    {
        return $this->type === 'event';
    }

    /**
     * Check if this event has a registration form.
     */
    public function hasRegistrationForm(): bool
    {
        return $this->isEvent() && ! empty($this->form_schema);
    }
}
