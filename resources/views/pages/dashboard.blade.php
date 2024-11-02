@extends('layouts.logged-user')

@section('content')

    <div class="flex flex-col items-center gap-5">
        <p>
            What would you like to do?
        </p>
        <div class="w-[30%] flex flex-col gap-3">
            <a href="/play" class="bg-indigo-600 hover:bg-indigo-700 transition text-center rounded text-white py-1">
                Play against opponent
            </a>
            <div class="bg-indigo-600 hover:bg-indigo-700 transition text-center rounded text-white py-1">
                Play against stockfish (not available)
            </div>
            <div class="bg-indigo-600 hover:bg-indigo-700 transition text-center rounded text-white py-1">
                Play against custom engine (not available)
            </div>
        </div>
    </div>
    
@stop