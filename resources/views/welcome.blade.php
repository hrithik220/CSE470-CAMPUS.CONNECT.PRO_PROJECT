<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Connect Pro — Campus Marketplace & Community</title>
    <meta name="description" content="Buy, sell, and connect with students on campus. Join the sustainable campus marketplace.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{brand:{400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca'},surface:{900:'#0f172a',950:'#020617'}}}}}</script>
    <style>
        .glass{background:rgba(30,41,59,.6);backdrop-filter:blur(20px);border:1px solid rgba(99,102,241,.12)}
        .gradient-text{background:linear-gradient(135deg,#818cf8,#6366f1,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .hero-glow{position:absolute;width:500px;height:500px;border-radius:50%;filter:blur(120px);opacity:.15}
        .float{animation:float 6s ease-in-out infinite}
        @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-20px)}}
        .fade-up{animation:fadeUp .8s ease-out forwards;opacity:0}
        @keyframes fadeUp{to{opacity:1;transform:translateY(0)}}
        .fade-up{transform:translateY(30px)}
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-surface-950 text-gray-200 font-sans antialiased overflow-x-hidden">
    {{-- Glows --}}
    <div class="hero-glow bg-brand-500 top-[-200px] left-[-100px] fixed"></div>
    <div class="hero-glow bg-purple-600 bottom-[-200px] right-[-100px] fixed"></div>

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-bold shadow-lg shadow-brand-500/25">C</div>
                <span class="text-lg font-bold gradient-text">CampusConnect Pro</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">Sign In</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-medium bg-brand-600 hover:bg-brand-500 text-white rounded-lg transition shadow-lg shadow-brand-600/25">Get Started</a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
            <div class="fade-up" style="animation-delay:.1s">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-xs font-medium mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Now live for your campus
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Buy, Sell &<br><span class="gradient-text">Connect</span> on Campus
                </h1>
                <p class="text-lg text-gray-400 mb-8 max-w-lg">The marketplace built for students. Trade textbooks, electronics, and more while earning karma points and making your campus sustainable.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition shadow-xl shadow-brand-600/30 flex items-center gap-2">
                        Join Now <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-3.5 glass text-gray-300 hover:text-white font-semibold rounded-xl transition">Sign In →</a>
                </div>
                <div class="flex items-center gap-8 mt-10 text-sm text-gray-500">
                    <div><span class="text-2xl font-bold text-white">500+</span><br>Active Users</div>
                    <div><span class="text-2xl font-bold text-white">2.5K</span><br>Items Traded</div>
                    <div><span class="text-2xl font-bold text-white">1.2T</span><br>kg CO₂ Saved</div>
                </div>
            </div>
            <div class="hidden lg:block fade-up float" style="animation-delay:.3s">
                <div class="glass rounded-2xl p-6 space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                    @foreach([['book','Calculus Textbook','৳45','textbooks'],['laptop','MacBook Air M1','৳650','electronics'],['trophy','Tennis Racket','৳90','sports']] as $item)
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition text-brand-400">
                        <div class="w-12 h-12 rounded-lg bg-brand-500/10 flex items-center justify-center">
                            <i data-lucide="{{ $item[0] }}"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-sm text-gray-200">{{ $item[1] }}</p>
                            <p class="text-xs text-gray-500">{{ $item[3] }}</p>
                        </div>
                        <span class="text-brand-400 font-bold">{{ $item[2] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 fade-up">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Everything you need, <span class="gradient-text">one platform</span></h2>
                <p class="text-gray-400 max-w-2xl mx-auto">From trading textbooks to tracking your sustainability impact—we've got your campus life covered.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['shopping-bag','Campus Marketplace','List and discover items from fellow students. Trade textbooks, electronics, furniture and more.','from-blue-500/10 to-cyan-500/10','border-blue-500/20'],
                    ['zap','Karma & Rewards','Earn karma for every transaction. Climb the leaderboard and unlock exclusive badges.','from-amber-500/10 to-orange-500/10','border-amber-500/20'],
                    ['leaf','Sustainability','Track your environmental impact. See CO₂ saved and items reused across campus.','from-green-500/10 to-emerald-500/10','border-green-500/20']
                ] as $f)
                <div class="glass rounded-2xl p-6 hover:border-brand-500/30 transition group text-brand-400">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $f[3] }} border {{ $f[4] }} flex items-center justify-center mb-5">
                        <i data-lucide="{{ $f[0] }}" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-100">{{ $f[1] }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $f[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="glass rounded-3xl p-12">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Ready to <span class="gradient-text">get started?</span></h2>
                <p class="text-gray-400 mb-8 max-w-xl mx-auto">Join hundreds of students already trading on Campus Connect Pro. Sign up with your university email and start today.</p>
                <a href="{{ route('register') }}" class="inline-flex px-10 py-4 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition shadow-xl shadow-brand-600/30 text-lg">Create Free Account</a>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/5 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Campus Connect Pro. Built with ❤️ for students.</p>
        </div>
    </footer>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
