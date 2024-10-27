<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/register', function () {
    return view('pages.register');
});

Route::get('/login', function() {
    return view('pages.login');
});

Route::get('/dashboard', function() {
    return view('pages.dashboard');
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
