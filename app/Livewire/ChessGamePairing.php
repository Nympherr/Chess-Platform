<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\PlayerPaired;
use App\Events\MoveMade;
use App\Events\GameFinished;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ChessGamePairing extends Component
{

    public $is_pairing = true;
    public $player_1 = null;
    public $player_2 = null;

    public function mount()
    {
        $this->pair_players();
    }

    public function pair_players()
    {

        $current_user = Auth::user()->id;
        $waiting_player = Cache::pull('waiting_player');

        if (!$waiting_player || $waiting_player == $current_user) {
            Cache::put('waiting_player', $current_user, now()->addMinutes(5));
            return;
        }

        $this->player_1 = \App\Models\User::find($waiting_player)->name;
        $this->player_2 = \App\Models\User::find($current_user)->name;

        broadcast(new PlayerPaired($this->player_1, $this->player_2));

        // For 2nd player
        $this->is_pairing = false;
    }

    protected $listeners = ['update_pairing', 'update_move', 'end_game'];

    public function update_pairing($player_1, $player_2)
    {
        $this->player_1 = $player_1;
        $this->player_2 = $player_2;
        $this->is_pairing = false;
    }

    public function update_move($move_source, $move_target, $player_turn)
    {
        broadcast(new MoveMade($move_source, $move_target, $player_turn));
    }

    public function end_game($result)
    {

        broadcast(new GameFinished($result, $this->player_1, $this->player_2));
    }

    public function render()
    {
        return view('livewire.chess-game-pairing');
    }
}
