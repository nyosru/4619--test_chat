<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * Экземпляр заказа.
     *
     * @var \App\Models\Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
      // * @param  \App\Models\Ticket $tic
     * @return void
     */
//    public function __construct(Ticket $tic ) {
    public function __construct($tic) {
        //
        $this->order = $tic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this
                ->from('support1@php-cat.com')
                ->view('emails.orders.shipped')
                        // текстовая версия
                        // ->text('emails.orders.shipped_plain')
                        // доп параметры в шаблон
                        ->with([
//                        'orderName' => $this->order->name,
                            'orderName' => 111,
//                        'orderPrice' => $this->order->price,
                            'orderPrice' => 222,
                            'mess' => 'mess in ' . __FILE__ . ' ' . __LINE__
        ]);
    }

}
