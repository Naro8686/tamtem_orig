<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailAdminkaUpdateBallance extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $title;

    public function __construct($msg, $title)
    {
        $this->message = $msg;
        $this->title = $title;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
      //  $notifiable->email = $notifiable->user->email;
        return (new MailMessage())
            ->subject('Пополнение счета на сайте tamtem.ru')
            ->view('emails.email_adminka_update_ballance', ['msg' => $this->message, 'title'=>$this->title, 'userName' => $notifiable->name ])
            //->view('emails.email_moderate', ['message' => $this->message, 'user_name' => $this->user->name ])
            ->from(config('b2b.email.noreply'));
    }
}