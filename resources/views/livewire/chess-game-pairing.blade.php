<div>
    @if ($isPairing)
        <p>Pairing players...</p>
    @else
        <p>{{ $player1 }} vs {{ $player2 }}</p>
    @endif

    <button wire:click="pairPlayers" @if (!$isPairing) disabled @endif>
        Pair Players
    </button>
</div>