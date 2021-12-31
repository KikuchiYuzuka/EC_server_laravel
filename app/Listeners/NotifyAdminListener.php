<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use http\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class NotifyAdminListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;

        Mail::send('admin', ['order' => $order], function(Message $message){
            $message->subject('A Order has been competed');
            $message->to('admin.com');
        });
    }

}
