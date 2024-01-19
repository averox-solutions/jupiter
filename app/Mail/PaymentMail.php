<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    public $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = env('MAIL_FROM_ADDRESS'), $name = env('MAIL_FROM_NAME'))
            ->subject($this->payment->status == 'completed' ? __('Payment completed') : __('Payment cancelled') . ' | ' .getSetting('APPLICATION_NAME'))
            ->markdown('vendor.mail.html.payment');
    }
}
