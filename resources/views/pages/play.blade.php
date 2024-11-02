@extends('layouts.logged-user')

@php
    use App\Models\Chessboard;
@endphp

@vite('resources/js/chessboard.js')

@section('content')
    <div class="flex justify-center">
        <div class="border rounded">
            @foreach($board_data as $rank)
                <div class="flex">
                    @foreach($rank as $file)
                        @php
                            $square_color = $file['square_color'] == 'lightCol' ? 'bg-amber-700' : 'bg-slate-100';   
                        @endphp
                        <div class="{{ $square_color }} w-12 h-12 flex justify-center items-center square">
                            @if($file['piece'] != '0')
                                <img src="{{ Chessboard::get_piece_icon($file['piece']) }}" class="chess-piece cursor-pointer" alt="chess piece">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@stop