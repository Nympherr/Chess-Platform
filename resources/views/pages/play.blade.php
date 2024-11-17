@extends('layouts.logged-user')

@vite('resources/js/bootstrap.js')
@vite('resources/js/chessboard.js')

@section('content')
    <div class="flex justify-center">
        <div class="border rounded">
            <div id="myBoard" style="width: 400px"></div>

            <label>FEN:</label>
            <div id="gameFEN"></div>

            <div>
                @livewire('chess-game-pairing')
            </div>
        </div>
    </div>
@stop