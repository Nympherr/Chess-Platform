@extends('layouts.logged-user')

@vite('resources/js/chessboards/stockfish.js')

@section('content')
    <div class="flex justify-center">
        <div class="flex-col">
            <div class="border rounded">
                <div id="chessBoard" style="width: 400px;"></div>
            </div>
            <p class="mt-5" id="player-color">You play as: </p>
            <p class="mt-5" style="display: none" id="game-result">Result: </p>
            <p class="mt-5" id="player-move">Your move</p>
        </div>
    </div>

    <script>
        window.userData = @json($user);
    </script>
@stop