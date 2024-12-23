@extends('layouts.logged-user')

@section('content')

    <div class="flex flex-col items-center gap-5">
        <p>
            What would you like to do?
        </p>
        <div class="w-[30%] flex flex-col gap-3">
            <a href="/play" class="bg-indigo-600 hover:bg-indigo-700 transition text-center rounded text-white py-2">
                Play against real opponent
            </a>
            <a href="/stockfish" class="bg-indigo-600 hover:bg-indigo-700 transition text-center rounded text-white py-2">
                Play against stockfish
            </a>
            <div class="bg-indigo-600 opacity-50 text-center rounded text-white py-2">
                Play against custom engine (not available)
            </div>
        </div>
    </div>
    
@stop