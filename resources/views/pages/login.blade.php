@extends('layouts.default')

@section('content')

    <div class="flex flex-col justify-center items-center h-[60%]">
        <h2 class="text-2xl mb-4">
            Login page
        </h2>
        <div class="w-[30%] border p-5 rounded-lg">
            <form class="flex flex-col gap-4" action="/login" method="POST">
                @csrf
                <div class="flex flex-col">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" required name="email" placeholder="John@gmail.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                </div>
                <div class="flex flex-col">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" required placeholder="********" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                </div>
                <button type="submit" class="bg-purple-600 rounded-lg p-2 text-white font-bold hover:bg-purple-700 transition">Login</button>
                @error('email')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </div>
    
@stop