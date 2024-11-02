@extends('layouts.logged-user')

@section('content')
    <div class="flex justify-center">
        <div class="chessboard border rounded">
            @foreach($board_data as $rank)
                <div class="flex chessboard-rank">
                    @foreach($rank as $file)
                        @php
                            $square_color = $file['square_color'] == 'lightCol' ? 'bg-amber-700' : 'bg-slate-100';   
                        @endphp
                        <div class="{{ $square_color }} chessboard-file w-12 h-12"></div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@stop