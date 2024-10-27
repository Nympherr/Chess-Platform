@extends('layouts.default')

@section('content')

    <div class="flex flex-col justify-center items-center h-[60%]">
        <h2 class="text-2xl mb-4">
            New user form
        </h2>
        <div class="w-[30%] border p-5 rounded-lg">
            <form class="flex flex-col gap-4" action="/register" method="POST">
                <div class="flex flex-col">
                    <label for="sign-username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <input type="text" required name="sign-username" placeholder="User123" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                </div>
                <div class="flex flex-col">
                    <label for="sign-email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" required name="sign-email" placeholder="John@gmail.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                </div>
                <div class="flex flex-col">
                    <label for="sign-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" required placeholder="********" name="sign-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 outline-none">
                </div>
                <button type="submit" class="bg-purple-600 rounded-lg p-2 text-white font-bold hover:bg-purple-700 transition">Register</button>
            </form>
        </div>
    </div>

@stop