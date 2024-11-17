@extends('layouts.logged-user')

@vite('resources/js/bootstrap.js')
@vite('resources/js/chessboard.js')

@section('content')
    <div class="flex justify-center">
        <div>
            @livewire('chess-game-pairing')
        </div>
    </div>
@stop