@extends('layouts.logged-user')

@vite('resources/js/chessboard.js')

@php

$stockfish_path = storage_path('app/private/stockfish');

$descriptorspec = [
    0 => ["pipe", "r"],  // stdin
    1 => ["pipe", "w"],  // stdout
    2 => ["pipe", "w"]   // stderr
];

$process = proc_open($stockfish_path, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Send the command to Stockfish via stdin
    fwrite($pipes[0], "uci\n");  // "uci" is the Universal Chess Interface command for initialization
    fclose($pipes[0]);

    // Read the output from Stockfish's stdout
    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // You can read error messages (stderr) if needed
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    // Close the process
    proc_close($process);

    // Output Stockfish's response
    echo "Stockfish Output:\n";
    echo $output;
    echo "\nErrors:\n";
    echo $errors;
} else {
    echo "Error: Unable to start Stockfish process.";
}


@endphp

@section('content')
    <div class="flex justify-center">
        <div class="border rounded">
            <div id="chessBoard" style="width: 400px;" class="pointer-events-none"></div>
        </div>
    </div>
@stop