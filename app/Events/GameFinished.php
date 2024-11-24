<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class GameFinished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $result;
    public $player1;
    public $player2;

    public function __construct($result, $player1, $player2)
    {
        $this->result = $result;

        $player1_db = User::where('name', $player1)->first();
        $player2_db = User::where('name', $player2)->first();

        switch($result){
            case '1-0':
                $player1_db->won_games += 0.5;
                $player2_db->lost_games += 0.5;
                break;
            case '0-1':
                $player1_db->lost_games += 0.5;
                $player2_db->won_games += 0.5;
                break;
            case '1/2':
                $player1_db->drawn_games += 0.5;
                $player2_db->drawn_games += 0.5;
                break;
        }

        $player1_db->save();
        $player2_db->save();
    }

    public function broadcastOn()
    {
        return new Channel('chess-room');
    }

    public function broadcastAs()
    {
        return 'game.ended';
    }
}
