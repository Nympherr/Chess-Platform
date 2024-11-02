@php
    use Illuminate\Support\Facades\Auth;
@endphp

<header class="py-3 border-b border-slate-200 mb-12">
    <nav class="flex justify-between items-center">
        <a href="/dashboard" class="flex items-center gap-3">
            <img class="max-h-12 h-full" src="{{ asset('icons/header-logo.png') }}" alt="Site logo">
            <h3 class="font-bold">CHESS PLATFORM</h3>
        </a>
        <div class="flex gap-12">
            <a href="/user-profile" class="flex items-center gap-1">
                <img class="max-h-6 h-full" src="{{ asset('icons/user.png') }}" alt="view user profile">
                <span>{{ Auth::user()->name }}</span>
            </a>
            <div>
                <a href="/logout" class="bg-violet-600 hover:bg-violet-700 transition rounded py-1 px-4 text-white font-bold" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="/logout" method="POST" style="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>
</header>