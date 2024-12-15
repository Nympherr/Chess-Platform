@extends('layouts.logged-user')

@vite('resources/js/chessboards/analyse.js')

@section('content')

<p class="font-bold text-xl mb-3 text-center">Game review (result: {{ $game->result}})</p>
<div class="flex justify-center">
    <div class="flex-col">
        <div class="border rounded">
            <div id="chessBoard" style="width: 400px;"></div>
        </div>
        <div class="flex justify-between mt-3 items-center">
            <div>
                <p class="font-bold">White player</p>
                <p class="text-center">{{ $game->player1_name }}</p>
            </div>
            <div>
                <button class="rounded text-white bg-slate-400 p-3" id="previous-move"><-</button>
                <button class="rounded text-white bg-slate-400 p-3" id="next-move">-></button>
            </div>
            <div>
                <p class="font-bold">Black player</p>
                <p class="text-center">{{ $game->player2_name }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    window.gameData = @json($game);
</script>

@stop