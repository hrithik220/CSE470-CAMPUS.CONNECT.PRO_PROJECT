<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CampusConnect Pro')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background: #070b1a;
            color: white;
        }

        .glass {
            background: rgba(30, 41, 59, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            color: #e5e7eb;
            transition: 0.2s;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.18);
            color: #a5b4fc;
        }

        .nav-link-admin {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.10);
            transition: 0.2s;
            text-decoration: none;
        }

        .nav-link-admin:hover {
            background: rgba(239, 68, 68, 0.20);
            color: #fecaca;
        }

        /* ✅ GLOBAL INPUT FIX (VERY IMPORTANT) */
        input,
        textarea,
        select {
            background-color: #111827 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255,255,255,0.15) !important;
            padding: 10px;
            border-radius: 10px;
        }

        input::placeholder,
        textarea::placeholder {
            color: #9ca3af !important;
        }

        select option {
            background-color: #111827;
            color: #ffffff;
        }

        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        input[type="number"] {
            color-scheme: dark;
        }

        /* Fix labels visibility */
        label {
            color: #d1d5db;
            font-weight: 500;
        }

        .brand-btn {
            background: #4f46e5;
        }

        .brand-btn:hover {
            background: #6366f1;
        }
    </style>
</head>

<body class="min-h-screen">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-[#151b2d] border-r border-white/10 fixed left-0 top-0 bottom-0 overflow-y-auto">

        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-xl font-bold shadow-lg">
                    C
                </div>
                <div>
                    <h1 class="text-xl font-bold text-indigo-400">CampusConnect</h1>
                    <p class="text-xs text-gray-500 tracking-widest">PRO EDITION</p>
                </div>
            </div>
        </div>

        <nav class="p-4 space-y-6">

            <a href="{{ route('dashboard') }}" class="nav-link">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </a>

            <div>
                <p class="text-xs uppercase text-gray-500 px-4 mb-2">Marketplace</p>

                <a href="{{ route('marketplace.index') }}" class="nav-link">Browse Items</a>
                <a href="{{ route('marketplace.create') }}" class="nav-link">Sell Item</a>
                <a href="{{ route('marketplace.my-listings') }}" class="nav-link">My Listings</a>
                <a href="{{ route('chat.index') }}" class="nav-link">Messages</a>
                <a href="{{ route('transactions.index') }}" class="nav-link">Transactions</a>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500 px-4 mb-2">Campus Life</p>

                <a href="/rides" class="nav-link">Ride Sharing</a>
                <a href="/tutors" class="nav-link">Tutors</a>
                <a href="/tutoring-sessions" class="nav-link">Sessions</a>
                <a href="/doubt-forum" class="nav-link">Doubt Forum</a>
                <a href="/deadlines" class="nav-link">Deadlines</a>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500 px-4 mb-2">Community</p>

                <a href="/leaderboard" class="nav-link">Leaderboard</a>
                <a href="/sustainability" class="nav-link">Eco Impact</a>
            </div>

            @if(auth()->check() && auth()->user()->is_admin)
                <div>
                    <p class="text-xs uppercase text-red-400 px-4 mb-2">Admin</p>

                    <a href="{{ route('admin.dashboard') }}" class="nav-link-admin">
                        Fraud Detection
                    </a>

                    <a href="{{ route('admin.items') }}" class="nav-link">
                        Flagged Items
                    </a>
                </div>
            @endif

        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-72 flex-1">

        <header class="h-20 bg-[#151b2d] border-b border-white/10 flex items-center justify-between px-8">
            <h2 class="text-xl font-bold">@yield('page_title')</h2>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button>Logout</button>
            </form>
        </header>

        <section class="p-8">
            @yield('content')
        </section>

    </main>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>