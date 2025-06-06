<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->to([
                        'sales.mypowernepal@gmail.com',
                        'raahulpoudel2015@gmail.com',
                        'manu2721@gmail.com',
                        'agrharsh7932@gmail.com'
                    ])
                    ->subject('My Power - New Order Received')
                    ->view('emails.order_received') // Blade view for email
                    ->with(['order' => $this->order]);
    }
}
