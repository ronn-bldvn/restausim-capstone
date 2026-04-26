<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityGradedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $activity;
    public $student;
    public $section;
    public $activityUrl;

    public function __construct($session)
    {
        // Load relations to avoid lazy-loading issues (especially for queued mail)
        $session->loadMissing(['activity.section', 'user']);

        $this->session  = $session;
        $this->activity = $session->activity ?? null;
        $this->student  = $session->user ?? null;

        // Section can come from session OR from activity->section
        $this->section  = $session->section ?? ($this->activity->section ?? null);

        // Build activity URL (with safety checks)
        if ($this->section && $this->activity) {
            $this->activityUrl = route('student/activity/activity_details', [
                'section_id'  => $this->section->section_id,
                'activity_id' => $this->activity->activity_id
            ]);
        } else {
            $this->activityUrl = '#'; // fallback
        }
    }

    public function build()
    {
        $title = $this->activity
            ? 'Graded: ' . $this->activity->name
            : 'Your activity was graded';

        return $this->subject($title)
                    ->view('emails.activityGraded');
        // All public properties become available in the Blade:
        // $session, $activity, $student, $section, $activityUrl
    }
}

