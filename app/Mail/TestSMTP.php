<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestSMTP extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = env('MAIL_FROM_ADDRESS'), $name = env('MAIL_FROM_NAME'))
                    ->subject(getSetting('APPLICATION_NAME') . ' | ' .  __('Test Email'))
                    ->markdown('vendor.mail.html.test-smtp');
    }
}
