<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewClientNotification extends Notification
{
    use Queueable;

    public $password;
    public $email;

    public function __construct($password, $email)
    {
        $this->password = $password;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your New Account Information')
            ->line('Your email: ' . $this->email)
            ->line('Your password for the new account is: ' . $this->password)
            ->line('Please keep it secure and do not share it with anyone.');
    }

    public function toArray($notifiable)
    {
        return [
            // Notification data if needed for other channels
        ];
    }
}
