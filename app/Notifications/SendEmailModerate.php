<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailModerate extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $title;
    protected $urlToDeal;

    public function __construct($msg, $title, $urlToDeal = '')
    {
        $this->message = $msg;
        $this->title = $title;
        $this->urlToDeal = $urlToDeal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email = $notifiable->user->email;
        return (new MailMessage())
            ->subject('Модерация заявки tamtem.ru')
            ->view('emails.email_moderate', ['msg' => $this->message, 'title'=>$this->title, 'deal'=> $notifiable, 'url_to_deal' =>  $this->urlToDeal  ])
            //->view('emails.email_moderate', ['message' => $this->message, 'user_name' => $this->user->name ])
            ->from(config('b2b.email.noreply'));
    }
}