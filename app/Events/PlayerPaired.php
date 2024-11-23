<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PlayerPaired implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $player1;
    public $player2;

    public function __construct($player1, $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    //TODO
    public function broadcastOn()
    {
        Log::info('Broadcasting PlayerPaired event', ['player1' => $this->player1, 'player2' => $this->player2]);
        return new Channel('chess-room');
    }

    public function broadcastAs()
    {
        $eventName = 'players.paired';

        // Log the event name being broadcast
        Log::info("Broadcasting event: $eventName");
    
        return $eventName;
    }
}
