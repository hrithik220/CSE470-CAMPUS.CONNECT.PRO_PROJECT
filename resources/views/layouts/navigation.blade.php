<nav x-data="{ open: false }" style="background:#1f2937; padding:10px 20px;">
    <div style="max-width:1200px; margin:0 auto; display:flex; justify-content:space-between; align-items:center;">

        <!-- LEFT SIDE -->
        <div style="display:flex; align-items:center; gap:20px;">

            <!-- Logo -->
            <a href="/dashboard" style="color:white; font-weight:700; font-size:18px;">
                CampusConnect
            </a>

            <!-- Navigation Links -->
            <a href="/dashboard" style="color:white; text-decoration:none;">Dashboard</a>

            <a href="/tutors" style="color:white; text-decoration:none;">
                Tutors
            </a>

            <a href="/tutoring-sessions" style="color:white; text-decoration:none;">
                Sessions
            </a>

            <a href="/doubt-forum" style="color:white; text-decoration:none;">
                Doubt Forum
            </a>

        </div>

        <!-- RIGHT SIDE -->
        <div style="display:flex; align-items:center; gap:15px;">

            <span style="color:white;">
                {{ Auth::user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        style="background:#dc2626; color:white; padding:6px 12px; border:none; border-radius:6px;">
                    Logout
                </button>
            </form>

        </div>

    </div>
</nav>