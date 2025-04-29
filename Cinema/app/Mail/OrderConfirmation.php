<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $qrCode;

    public function __construct($order, $qrCode)
    {
        $this->order = $order;
        $this->qrCode = $qrCode;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre commande de billets')
                    ->view('emails.order_confirmation');
    }
}