<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification; // Import Notification class

class EmailNotification extends Notification // Change class name to EmailNotification
{
    use Queueable;

    public $details; // Store data to be included in the email

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Pendaftaran Volunteer Berhasil') 
                    ->line('Terima kasih telah mendaftar sebagai Volunteer.')
                    ->line('Berikut detail akun Anda:')
                    ->line('Email: ' . $this->details['email'])
                    ->line('Password: ' . $this->details['password']);
    }
}