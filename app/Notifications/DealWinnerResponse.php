<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DealWinnerResponse extends Notification implements ShouldQueue
{
    use Queueable;

    protected $urlToDeal;
    protected $amount;

    public function __construct($urlToDeal, $amount)
    {
        $this->urlToDeal = $urlToDeal;
        $this->amount = $amount;

    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email = $notifiable->winner->owner->email;
        return (new MailMessage())
            ->subject('Вас выбрали победителем в заказе')
            ->view('emails.deal_winner_response', ['deal' => $notifiable, 'amount' =>  $this->amount, 'url_to_deal' =>  $this->urlToDeal])
            ->from(config('b2b.email.noreply'));
    }
}