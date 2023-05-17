<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisterConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->rollConfirmKey();

        return (new MailMessage())
            ->subject('Завершение регистрации на сайте tamtem.ru')
            ->view('emails.register_confirmation', ['user' => $notifiable])
            ->bcc(config('b2b.email.shan_it_dir'))
            ->from(config('b2b.email.noreply'));
    }
}
