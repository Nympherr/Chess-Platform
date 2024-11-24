@extends('layouts.logged-user')

@php

    // Fetching user data
    $user = Auth::user();
    $user_id = $user->id;
    $user_email = $user->email;
    $user_name = $user->name;
    $won_games_count = $user->won_games;
    $lost_games_count = $user->lost_games;
    $drawn_games_count = $user->drawn_games;

    // Retrieving user creation date
    $user_creation_date = DB::table('users')
        ->where('id', $user_id)
        ->value(DB::raw("strftime('%Y-%m-%d', created_at)"));

    // Calculating how long user is registered
    $user_creation_date_object = new DateTime($user_creation_date);
    $current_date = new DateTime();
    $days_user_is_registed = $current_date->diff($user_creation_date_object)->days;
@endphp

@section('content')

    <div class="flex gap-10">

        <div class="w-1/3">
            <h3 class="font-bold mb-5">Edit profile info</h3>

            <form action="/change-user-info" method="POST" class="flex flex-col gap-4">
                @csrf

                <div class="flex flex-col">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Change username</label>
                    <input type="text" required name="name" placeholder="User123" value="{{ $user_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="flex flex-col">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Change email</label>
                    <input type="email" required name="email" placeholder="John@gmail.com" value="{{ $user_email }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                    @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="bg-purple-600 w-full   rounded-lg p-2 text-white font-bold hover:bg-purple-700 transition">Save changes</button>
            </form>
            @if(session('success'))
                <p class="text-green-600 mt-2">{{ session('success') }}</p>
            @endif
        </div>

        <div class="w-1/3">

            <h3 class="font-bold mb-5">Change password</h3>
            <form action="/update-password" method="POST" class="flex flex-col gap-4">
                @csrf

                <div class="flex flex-col">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current password</label>
                    <input type="password" required placeholder="********" name="current_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                    @if(session('current_password_error'))
                        <p class="text-red-500 mt-2">{{ session('current_password_error') }}</p>
                    @endif
                </div>

                <div class="flex flex-col">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New password</label>
                    <input type="password" required placeholder="********" name="new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                    @error('new_password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Repeat new password</label>
                    <input type="password" required placeholder="********" name="new_password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                    @error('new_password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="bg-purple-600 w-full   rounded-lg p-2 text-white font-bold hover:bg-purple-700 transition">Update password</button>
            </form>
            @if(session('password_change_success'))
                <p class="text-green-600 mt-2">{{ session('password_change_success') }}</p>
            @endif
        </div>
    
        <div class="w-1/3">

            <h3 class="font-bold mb-5">General info</h3>
            <span>
                User created at: {{ $user_creation_date }} ({{ $days_user_is_registed }}d ago)
            </span>
        
            {{-- TODO --}}
            <div>
                <p class="text-green-600 font-bold">Won games: {{ $won_games_count }}</p>
                <p class="text-red-600 font-bold">Lost games: {{ $lost_games_count }}</p>
                <p class="font-bold">Drawn games: {{ $drawn_games_count }}</p>
            </div>
        </div>
    </div>
@stop