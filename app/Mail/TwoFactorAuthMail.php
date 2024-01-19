<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorAuthMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = env('MAIL_FROM_ADDRESS'), $name = getSetting('APPLICATION_NAME'))
            ->subject(getSetting('APPLICATION_NAME') . ' | ' .  __('Two Factor Authentication'))
            ->markdown('vendor.mail.html.two_factor_auth');
    }
}
