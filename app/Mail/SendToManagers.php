<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendToManagers extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;
    public $title;
    public $subj;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg, $title = 'Напомнить клиенту об оплате !!!', $subj = 'TamTem, новый победитель, с неоплаченными контактами.')
    {
        $this->msg = $msg;
        $this->title = $title;
        $this->subj = $subj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_to_managers', ['msg' => $this->msg, 'title' => $this->title])
            ->subject($this->subj)
            ->from(config('b2b.email.managers'));
    }
}
