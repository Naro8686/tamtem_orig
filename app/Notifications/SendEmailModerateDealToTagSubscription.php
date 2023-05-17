<?php

namespace App\Notifications;

use App\Models\Org\OrganizationNotification;
use App\Notifications\Channels\DatabaseCustomChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailModerateDealToTagSubscription extends Notification implements ShouldQueue
{
    use Queueable;

    protected $email;
    protected $userName;
    protected $urlToDeal;

    protected $dealName;
    protected $dqh_specification;
    protected $dqh_volume;
    protected $unit_for_all;
    protected $dqh_type_deal;
    protected $dqh_min_party;
    protected $url_to_unsubscribe;

 
    //  public function __construct($orgCat, $user)
    public function __construct($userEmail, $userName, $urlToDeal, $dealName, $dqh_specification, $dqh_volume, $unit_for_all, $dqh_type_deal, $dqh_min_party, $url_to_unsubscribe)
    {
        $this->email = $userEmail;
        $this->userName = $userName;
        $this->urlToDeal = $urlToDeal;

        $this->dealName =  $dealName; 
        $this->dqh_specification =  $dqh_specification;    
        $this->dqh_volume =  $dqh_volume; 
        $this->unit_for_all =  $unit_for_all; 
        $this->dqh_type_deal =  $dqh_type_deal; 
        $this->dqh_min_party =  $dqh_min_party; 
        $this->url_to_unsubscribe =  $url_to_unsubscribe; 
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $notifiable->email = $this->email;
        $urlToDeal = $notifiable->id;
        return (new MailMessage())
            ->subject('Заказ: ' . $this->dealName)
            ->view('emails.deal_moderate_tags_subcription', 
                [
                    'user_name' => $this->userName, 
                    'dqh_specification' => $this->dqh_specification,
                    'dqh_volume_unit_for_all' => $this->dqh_volume . " " .  $this->unit_for_all, 
                    'url_to_deal' =>  $this->urlToDeal, 
                    'dqh_type_deal' =>  $this->dqh_type_deal, 
                    'dqh_min_party' =>  $this->dqh_min_party, 
                    'deal_name' =>  $this->dealName,
                    'url_to_unsubscribe' =>  $this->url_to_unsubscribe,
                ])
            ->from(config('b2b.email.noreply'));
    }
}
