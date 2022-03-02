<?php

namespace App\Events;

use App\Models\User;
use App\Models\Xlsform;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeployXlsFormComplete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Xlsform $xlsform;
    public ?User $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($xlsform, User $user = null)
    {
        $this->xlsform = Xlsform::find($xlsform);
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channel = $this->user ? $this->user->id : 'admin';
        return new PrivateChannel("App.Models.User.{$channel}");
    }
}
