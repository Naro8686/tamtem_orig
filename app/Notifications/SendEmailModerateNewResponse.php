<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailModerateNewResponse extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $email;
    protected $userName;
    protected $dealName;
    protected $dealId;
    protected $urlToDeal;

    public function __construct($title, $email, $userName, $dealName, $dealId, $urlToDeal)
    {
        $this->title = $title;
        $this->email = $email;
        $this->userName = $userName;
        $this->dealName = $dealName;
        $this->dealId = $dealId;
        $this->urlToDeal = $urlToDeal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email = $this->email;
        return (new MailMessage())
            ->subject('Новое предложение на tamtem.ru по вашему заказу')
            ->view('emails.email_moderate_new_response', ['title'=>$this->title, 'userName' => $this->userName, 'dealName' => $this->dealName, 'dealId' => $this->dealId, 'url_to_deal' =>  $this->urlToDeal])
            //->view('emails.email_moderate', ['message' => $this->message, 'user_name' => $this->user->name ])
            ->from(config('b2b.email.noreply'));
    }
}