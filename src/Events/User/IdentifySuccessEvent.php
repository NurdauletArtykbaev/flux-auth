<?php

namespace Nurdaulet\FluxAuth\Events\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IdentifySuccessEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    /**
     * Create a new event instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
