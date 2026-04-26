<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SectionInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;
    public $section;
    public $student;
    public $faculty;

    public function __construct($invite, $section, $faculty = null)
    {
        $this->invite = $invite;
        $this->section = $section;
        $this->faculty = $faculty;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are invited to join a RestauSim class',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
