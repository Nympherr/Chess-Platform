<?php

use Illuminate\Support\Facades\Route;

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
});

require __DIR__.'/auth.php';
