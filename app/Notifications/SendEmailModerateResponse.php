<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailModerateResponse extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $email;
    protected $userName;
    protected $dealName;
    protected $dealId;
    protected $status;

    public function __construct($title, $email, $userName, $dealName, $dealId, $status)
    {
        $this->title = $title;
        $this->email = $email;
        $this->userName = $userName;
        $this->dealName = $dealName;
        $this->dealId = $dealId;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email = $this->email;
        return (new MailMessage())
            ->subject('Модерация отклика на сайте tamtem.ru')
            ->view('emails.email_moderate_response', ['title'=>$this->title, 'userName' => $this->userName, 'dealName' => $this->dealName, 'dealId' => $this->dealId, 'status' => $this->status ])
            //->view('emails.email_moderate', ['message' => $this->message, 'user_name' => $this->user->name ])
            ->from(config('b2b.email.noreply'));
    }
}