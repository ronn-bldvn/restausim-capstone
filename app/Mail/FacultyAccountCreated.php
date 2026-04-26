<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacultyAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rawPassword;

    public function __construct($user, $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }

    public function build()
    {
        return $this->subject('Your Faculty Account Credentials')
                    ->view('emails.FacultyCredentials');
    }
}
