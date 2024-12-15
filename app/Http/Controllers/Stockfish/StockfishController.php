<?php

namespace App\Http\Controllers\Stockfish;

use App\Http\Controllers\Controller;
use App\Models\ChessGame;
use App\Models\User;
use Illuminate\Http\Request;

class StockfishController extends Controller
{
    /**
     * Sends stockfish move to the frontend
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function send_stockfish_move(Request $request)
    {
        $current_position = $request->input('gamePosition');
        $computer_move = $this->get_stockfish_move($current_position);

        return response()->json(['computerMove' => $computer_move]);
    }

    /**
     * Gets stockfish move
     */
    public function get_stockfish_move($game_position)
    {
        $stockfish_path = storage_path('app/private/stockfish');

        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
        ];

        $process = proc_open($stockfish_path, $descriptorspec, $pipes);

        if (is_resource($process)) {
            fwrite($pipes[0], "uci\nposition fen {$game_position}\ngo movetime 2000");
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            proc_close($process);
            
            $lines = explode("\n", $output);
            $lines = array_filter($lines, 'strlen');
            $last_line = end($lines);
            $last_line = str_replace(' ', '', $last_line);
            $best_move = str_replace('bestmove', '',$last_line);

            return $best_move;
        } else {
            return 'error';
        }
    }

    /**
     * Saves game to database
     */
    public function finish_game(Request $request)
    {
        $user_id = $request->input('id');
        $player_color = $request->input('color');
        $game_finish_fen = $request->input('fen');
        $result = $request->input('result');
        $history = $request->input('history');

        $user = User::find($user_id);
        $user_name = $user->name;

        $is_player_1 = $player_color == "w"? true : false;

        $chess_game = ChessGame::create([
            'player1_id' => $is_player_1 ? $user_id : 'stockfish',
            'player2_id' => $is_player_1 ? 'stockfish' : $user_id,
            'player1_name' => $is_player_1 ? $user_name : 'stockfish',
            'player2_name' => $is_player_1 ? 'stockfish' : $user_name,
            'result' => $result,
            'game_finish_fen' => $game_finish_fen,
            'game_history' => $history
        ]);

        $chess_game->save();

        switch($result){
            case '1-0':
                $is_player_1 ? $user->won_games += 1 : $user->lost_games += 1;
                break;
            case '0-1':
                $is_player_1 ? $user->lost_games += 1 : $user->won_games += 1;
                break;
            case '1/2':
                $user->drawn_games += 1;
                break;
        }

        $user->save();

        return response()->json(['completed' => "Game has finished!"]);
    }
}
