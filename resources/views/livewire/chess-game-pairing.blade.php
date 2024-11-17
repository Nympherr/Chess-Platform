<div>
    @if ($isPairing)
        <p class="font-bold text-lg">Waiting for other player to join...</p>
    @else
        <div>
            <div class="border rounded">
                <div id="chessBoard" style="width: 400px"></div>
            </div>
    
            <div class="flex justify-between mt-4">
                <div>
                    <p class="font-bold">WHITE player</p>
                    <p>{{ $player1 }}</p>
                </div>
                <div>
                    <p class="font-bold">BLACK player</p>
                    <p>{{ $player2 }}</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <p>Result: <span id="game-result">N/A</span></p>
            <p>Current move: <span id="player-turn">white</span></p>
        </div>
    @endif
</div>