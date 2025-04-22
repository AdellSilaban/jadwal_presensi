<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The volunteer instance.
     *
     * @var Volunteer
     */
    public $volunteer;

    /**
     * The reset link.
     *
     * @var string
     */
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($volunteer, $resetLink)
    {
        $this->volunteer = $volunteer;
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Password')
                    ->view('reset_password') // Pastikan view yang benar
                    ->with([
                        'volunteer' => $this->volunteer,
                        'resetLink' => $this->resetLink,
                    ]);
    }
}