<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\PlayerPaired;

class ChessGamePairing extends Component
{

    public $isPairing = true;
    public $player1 = null;
    public $player2 = null;

    public function pairPlayers()
    {
        $this->isPairing = true;
        
        //TODO 
        sleep(3);
        
        //TODO 
        $this->player1 = 'Test 1';
        $this->player2 = 'Test 2';
        
        broadcast(new PlayerPaired($this->player1, $this->player2));

        $this->isPairing = false;
    }

    public function render()
    {
        return view('livewire.chess-game-pairing');
    }
}
