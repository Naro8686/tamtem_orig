<?php

namespace App\Notifications;

use App\Events\NotifyUser;
use App\Notifications\Channels\SocketChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class UserNewDealBuyMessage extends Notification
{
    use Queueable;

    private $message;
    private $user;
    private $deal;

    public function __construct($message, $user, $deal)
    {
        $this->message = $message;
        $this->user = $user;
        $this->deal = $deal;
    }

    public function via()
    {
        return [SocketChannel::class];
    }

    public function toSocket()
    {
        broadcast(new NotifyUser($this->message, $this->user, $this->deal));
    }
}
