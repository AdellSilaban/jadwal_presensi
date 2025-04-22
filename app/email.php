<?php

namespace App\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class email extends Mailable
{
    
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Terima kasih telah mendaftar.')
            ->action('Berikut Email dan Password yang dapat anda gunakan untuk login akun volunteer.')
            ->line('Best Regard -LPKKSK UKDW');
    }
}
