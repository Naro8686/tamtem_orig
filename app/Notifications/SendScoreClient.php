<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendScoreClient extends Notification implements ShouldQueue
{
    use Queueable;


    protected $email;
    protected $link;

    public function __construct($email, $link)
    {

        $this->email = $email;
        $this->link = $link;

    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email=$this->email;

        return (new MailMessage())
            ->subject('Cчет на оплату на сайте tamtem.ru')
            ->view('emails.send_score_client', ['msg' => 'Привет я письмо', 'user'=>$notifiable])
            ->attach('http://localhost:8000'.$this->link)
            ->from(config('b2b.email.noreply'));
    }
}