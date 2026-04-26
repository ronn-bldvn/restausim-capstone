<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivityDueReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $activity,
        public $daysUntilDue
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->daysUntilDue === 0
            ? 'Due Today: ' . $this->activity->name
            : 'Due Tomorrow: ' . $this->activity->name;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deadlineReminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
