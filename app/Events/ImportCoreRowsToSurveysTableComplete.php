<?php

namespace App\Events;

use App\Models\CoreVersion;
use App\Models\User;
use App\Models\Xlsform;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCoreRowsToSurveysTableComplete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CoreVersion $coreVersion;
    public ?User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($coreVersion, User $user = null)
    {
        $this->coreVersion = CoreVersion::find($coreVersion);
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
