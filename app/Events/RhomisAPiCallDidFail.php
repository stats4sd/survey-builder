<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Client\Response;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RhomisAPiCallDidFail implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Response $response;
    public array $headers;
    public string $body;
    public int $code;
    public bool $serverError;
    public bool $clientError;
    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Response $response, User $user)
    {
        $this->response = $response;
        $this->user = $user;

        // unpacked response variables for easier debugging
        $this->requestUrl = $response->effectiveUri();
        $this->code = $response->status();
        $this->body = $response->body();
        $this->serverError = $response->serverError();
        $this->clientError = $response->clientError();
        $this->headers = $response->headers();

        Log::error("RHOMIS API Error:");
        Log::error($response->effectiveUri());
        Log::error("Code: " . $this->code);
        Log::error("Body: " . $this->body);
        Log::error("serverError: " . $this->serverError);
        Log::error("clientError: " . $this->clientError);
        Log::error("Headers: " . collect($this->headers)->join(', '));

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        $channel = $this->user ? $this->user->id : 'admin';
        return new PrivateChannel("App.Models.User.{$channel}");
    }


}
