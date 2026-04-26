<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewActivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activity;
    public $section;
    public $student;
    public $faculty; // <--- Added this

    // Update constructor to accept $faculty
    public function __construct($activity, $section, $student, $faculty)
    {
        $this->activity = $activity;
        $this->section = $section;
        $this->student = $student;
        $this->faculty = $faculty; // <--- Assign it
    }

    public function build()
    {
        $activityUrl = route('student/activity/activity_details', [
            'section_id' => $this->section->section_id,
            'activity_id' => $this->activity->activity_id
        ]);

        return $this->subject('New activity posted: ' . $this->activity->name)
                    ->view('emails.newActivity')
                    ->with([
                        'activity' => $this->activity,
                        'section' => $this->section,
                        'student' => $this->student,
                        'faculty' => $this->faculty,
                        'activityUrl' => $activityUrl,
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->embed(public_path('images/fav-logo/logo-ver3.png'), 'restausim-logo');
                    });
    }
}
