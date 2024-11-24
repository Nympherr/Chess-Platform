<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoveMade implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $move_source;
    public $move_target;
    public $player_turn;

    public function __construct($move_source, $move_target, $player_turn)
    {
        $this->move_source = $move_source;
        $this->move_target = $move_target;
        $this->player_turn = $player_turn;
    }

    public function broadcastOn()
    {
        return new Channel('chess-room');
    }

    public function broadcastAs()
    {
        return 'move.made';
    }
}
