<?php

namespace App\Notifications;

use App\Notifications\Channels\DatabaseCustomChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Org\OrganizationNotification;

class DealNeedNewWinner extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        //return ['mail', DatabaseCustomChannel::class];
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email = $notifiable->user->email;

        return (new MailMessage())
            ->subject('TamTem, необходимо выбрать нового победителя среди поставщиков')
            ->view('emails.deal_need_new_winner', ['deal' => $notifiable])
            ->from(config('b2b.email.noreply'));
    }

    // public function toDatabase($notifiable)
    // {
    //     return new OrganizationNotification([
    //         'organization_id' => $notifiable->winner->id,
    //         'props' => json_encode([
    //             'message' => 'notifications.deal.need_new_winner',
    //             'props' => [
    //                 'deal_id' => $notifiable->id,
    //                 'deal_name' => $notifiable->name
    //             ]
    //         ]),
    //     ]);
    // }
}
