<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campus Connect Pro — @yield('title', 'Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center shadow">
        <a href="/" class="text-xl font-bold">Campus Connect Pro</a>
        <div class="flex gap-4 items-center">
            <a href="{{ route('marketplace.index') }}" class="hover:underline">Marketplace</a>
            <a href="{{ route('inbox') }}" class="hover:underline">Inbox</a>
            @auth
                <span class="text-sm">Hello, {{ Auth::user()->name }}</span>
                <form method="POST" action="/logout">
                    @csrf
                    <button class="bg-white text-blue-700 px-3 py-1 rounded text-sm font-semibold">Logout</button>
                </form>
            @else
                <a href="/login"
                 class="hover:underline">Login</a>
                <a href="/register" class="bg-white text-blue-700 px-3 py-1 rounded text-sm font-semibold">Register</a>
            @endauth
        </div>
    </nav>

    <!-- Flash messages -->
    <div class="max-w-6xl mx-auto mt-4 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Page content -->
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
