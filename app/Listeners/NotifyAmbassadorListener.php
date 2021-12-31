<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use http\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAmbassadorListener
{

    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;

        \Mail::send('ambassador', ['order' => $order], function(Message $message) use ($order) {
            $message->subject('A Order has been competed');
            $message->to('admin.com');
        });
    }
}
