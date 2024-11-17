<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\PlayerPaired;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ChessGamePairing extends Component
{

    public $isPairing = true;
    public $player1 = null;
    public $player2 = null;

    public function mount()
    {
        $this->pair_players();
    }

    public function pair_players()
    {

        $current_user = Auth::user()->id;

        $waiting_player = Cache::pull('waiting_player');

        if (!$waiting_player) {
            Cache::put('waiting_player', $current_user, now()->addMinutes(5));
            return;
        }

        if($waiting_player == $current_user){
            return;
        }

        $this->player1 = \App\Models\User::find($waiting_player)->name;
        $this->player2 = \App\Models\User::find($current_user)->name;
        $this->isPairing = false;

        broadcast(new PlayerPaired($this->player1, $this->player2));
        Cache::pull('waiting_player');
    }

    public function render()
    {
        return view('livewire.chess-game-pairing');
    }
}
