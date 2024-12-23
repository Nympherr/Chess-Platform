<div>
    @if($is_pairing)
        <p class="font-bold text-lg mb-3">Waiting for other player to join...</p>
    @endif
    <div>

        <div class="flex gap-5">
            <div class="border rounded">
                <div id="chessBoard" style="width: 400px;" class="pointer-events-none" wire:ignore></div>
            </div>
            <div class="flex flex-col justify-between">
                <div class="flex flex-col items-center gap-2">
                    <p id="black-name">{{ $player_2 }}</p>
                    <div class="font-bold border-solid border-2 border-gray-900 px-4 py-1 text-center" id="black-time" wire:ignore>
                        05:00
                    </div>
                </div>

                <p class="text-xl" id="player-move" style="display: none" wire:ignore>Your move</p>
                <p class="text-xl" id="game-end-text" style="display: none" wire:ignore>Game finished</p>

                <div class="flex flex-col items-center gap-2">
                    <div class="font-bold border-solid border-2 border-gray-900 px-4 py-1 text-center" id="white-time" wire:ignore>
                        05:00
                    </div>
                    <p id="white-name">test</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5" wire:ignore>
        <p id="game-result-div" class="hidden">Result: <span id="game-result">N/A</span></p>
    </div>
</div>