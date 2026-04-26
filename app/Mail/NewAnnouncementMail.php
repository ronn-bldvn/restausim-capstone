<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $announcementContent;
    public $section;
    public $student;                                                
    public $faculty;

    public function __construct($announcementContent, $section, $student, $faculty = null)
    {
        $this->announcementContent = $announcementContent;
        $this->section = $section;
        $this->student = $student;
        $this->faculty = $faculty;
    }

    public function build()
    {
        return $this->subject('New announcement in ' . $this->section->section_name)
                    ->view('emails.newAnnouncement');
    }
}
