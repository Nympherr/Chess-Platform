<?php

namespace App\Http\Controllers\Game;

use App\Models\ChessGame;
use App\Http\Controllers\Controller;

class AnalyseGameController extends Controller
{

    public function pass_game_data($game_id)
    {
        $game = ChessGame::find($game_id);

        if(!$game) {
            return abort(404, "Game not found");
        }

        return view('pages.analyse', ['game' => $game]);
    }
}
