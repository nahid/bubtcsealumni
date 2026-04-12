<?php

namespace App\Notifications;

use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobMatchesTagNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public JobPost $jobPost, public string $matchedTag) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New job matches your tag: #{$this->matchedTag}")
            ->greeting("Hi {$notifiable->name},")
            ->line("A new job \"{$this->jobPost->title}\" was posted matching your subscribed tag #{$this->matchedTag}.")
            ->action('View Job', route('jobs.show', $this->jobPost))
            ->line('Thank you for being part of the BUBTAlumni network!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_post_id' => $this->jobPost->id,
            'title' => $this->jobPost->title,
            'matched_tag' => $this->matchedTag,
        ];
    }
}
