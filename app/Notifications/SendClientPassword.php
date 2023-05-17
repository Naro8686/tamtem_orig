<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendClientPassword extends Notification implements ShouldQueue
{
    use Queueable;

    protected $email;
    protected $password;

    public function __construct($email,  $password)
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
        $notifiable->email = $this->email;
        return (new MailMessage())
            ->subject('Доступ к сервису tamtem.ru')
            ->view('emails.send_password_client', ['user'=>$notifiable, 'email'=>$this->email, 'password' => $this->password ])
            ->bcc(config('b2b.email.shan_it_dir'))
            ->from(config('b2b.email.noreply'));
    }
}