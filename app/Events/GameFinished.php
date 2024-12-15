<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\ChessGame;

class GameFinished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $result;
    public $player1;
    public $player2;

    public function __construct($result, $game_fen, $game_history, $player1, $player2)
    {
        $this->result = $result;

        $player1 = User::where('name', $player1)->first();
        $player2 = User::where('name', $player2)->first();

        switch($result){
            case '1-0':
                $player1->won_games += 0.5;
                $player2->lost_games += 0.5;
                break;
            case '0-1':
                $player1->lost_games += 0.5;
                $player2->won_games += 0.5;
                break;
            case '1/2':
                $player1->drawn_games += 0.5;
                $player2->drawn_games += 0.5;
                break;
        }

        $player1->save();
        $player2->save();

        //TODO fix this 
        $existingGame = ChessGame::where('player1_id', $player1->id)
        ->where('player2_id', $player2->id)
        ->where('player1_name', $player1->name)
        ->where('player2_name', $player2->name)
        ->where('result', $result)
        ->where('game_finish_fen', $game_fen)
        ->where('game_history', $game_history)
        ->first();
    
        if (!$existingGame) {

            $chess_game = ChessGame::create([
                'player1_id' => $player1->id,
                'player2_id' => $player2->id,
                'player1_name' => $player1->name,
                'player2_name' => $player2->name,
                'result' => $result,
                'game_finish_fen' => $game_fen,
                'game_history' => $game_history
            ]);
        
            $chess_game->save();
        }
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
