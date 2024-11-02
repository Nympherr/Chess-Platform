<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ChangeUserSettings;
use App\Models\Chessboard;

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
        return view('pages.play', ['board_data' => Chessboard::create_graphical_board()]);
    });

    Route::get('/user-profile', function() {
        return view('pages.user-profile');
    })->name('user-profile');

    Route::post('/change-user-info', [ChangeUserSettings::class, 'change_user_settings']);
    
    Route::post('/update-password', [ChangeUserSettings::class, 'update_password']);
});

require __DIR__.'/auth.php';
