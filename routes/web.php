<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ChangeUserSettings;
use App\Http\Controllers\Stockfish\StockfishController;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/', function() {
        return view('pages.home');
    })->name('home');

    Route::get('/register', function() {
        return view('pages.register');
    });

    Route::get('/login', function() {
        return view('pages.login');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function() {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/play', function() {
        return view('pages.play', ['username' => Auth::user()->name,]);
    });

    Route::get('/stockfish', function() {
        $user = Auth::id();
        return view('pages.stockfish', compact('user'));
    });

    Route::get('/user-profile', function() {
        return view('pages.user-profile');
    })->name('user-profile');

    Route::post('/change-user-info', [ChangeUserSettings::class, 'change_user_settings']);
    
    Route::post('/update-password', [ChangeUserSettings::class, 'update_password']);

    Route::post('/get-stockfish-move', [StockfishController::class, 'send_stockfish_move']);

    Route::post('/finish-stockfish-game', [StockfishController::class, 'finish_game']);
});

require __DIR__.'/auth.php';
