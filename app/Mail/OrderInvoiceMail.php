<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id, $file_path, $first_name, $last_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id, $file_path = "", $first_name = "", $last_name = "")
    {
        $this->order_id = $order_id;
        $this->file_path = $file_path;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-invoice')
        ->from('freebird-music-anton@gmail.com', 'Free Bird Music')
        ->subject('Račun' . $this->order_id);
    }
}
