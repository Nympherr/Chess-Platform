<div>
    @if($is_pairing)
        <p class="font-bold text-lg">Waiting for other player to join...</p>
    @endif
    <div>
        <div class="border rounded">
            <div id="chessBoard" style="width: 400px;" class="pointer-events-none" wire:ignore></div>
        </div>

        <div class="flex justify-between mt-4">
            <div>
                <p class="font-bold">WHITE player</p>
                <p>{{ $player_1 }}</p>
            </div>
            <div>
                <p class="font-bold">BLACK player</p>
                <p>{{ $player_2 }}</p>
            </div>
        </div>
    </div>

    <div class="mt-5" wire:ignore>
        <p id="game-result-div" class="hidden">Result: <span id="game-result">N/A</span></p>
        <p id="player-turn-div">Current move: <span id="player-turn">white</span></p>
    </div>
</div>