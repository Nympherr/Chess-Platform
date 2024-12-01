<?php

namespace App\Http\Controllers\Stockfish;

use App\Http\Controllers\Controller;
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
            fwrite($pipes[0], "uci\nposition fen {$game_position}\ngo movetime 5000");
            fclose($pipes[0]);

            sleep(3);

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
}
