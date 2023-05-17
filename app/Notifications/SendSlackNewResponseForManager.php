<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class SendSlackNewResponseForManager extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
		}
		public function toSlack($notifiable)
    {
				$message = "Есть новый отклик";
        
        return (new SlackMessage)
								->from('Tamtem', ':ghost:')
								//->to('#notification-test')
                ->to('#tamtem-ru')
                ->content('Информация: '.$message);
    }
}