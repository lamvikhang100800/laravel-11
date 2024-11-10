<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $message;
    public $url;
    public $user_id;

    public function __construct($data , $user_id)
    {   
        $this->type = $data->type;
        $this->message = $data->message;   
        $this->url = $data->url ?? null ;
        $this->user_id = $user_id;
    }
  
    public function broadcastOn()
    {
        return new Channel('user-' . $this->user_id);
    }
  
    public function broadcastAs()
    {
        return 'notification-event';
    }
}
