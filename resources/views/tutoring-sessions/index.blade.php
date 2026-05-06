<x-app-layout>
    <div style="background:#f3f4f6; min-height:100vh; padding:40px 0;">
        <div style="max-width:1200px; margin:0 auto; padding:0 24px;">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                <h2 style="font-size:32px; font-weight:700; color:#111827; margin:0;">
                    Tutoring Sessions
                </h2>

                <div style="display:flex; gap:12px;">
                    <a href="/tutors"
                       style="background:#2563eb; color:white; padding:10px 18px; border-radius:10px; text-decoration:none; font-weight:600; display:inline-block;">
                        View Tutors
                    </a>

                    <a href="/tutors/create"
                       style="background:#16a34a; color:white; padding:10px 18px; border-radius:10px; text-decoration:none; font-weight:600; display:inline-block;">
                        Add New Tutor
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="display:grid; grid-template-columns:1fr; gap:24px;">
                @forelse($sessions as $session)

                    @php
                        $sessionDateTime = \Carbon\Carbon::parse($session->session_date . ' ' . $session->session_time);
                        $now = \Carbon\Carbon::now();

                        if ($now->lessThan($sessionDateTime)) {
                            $diff = $now->diff($sessionDateTime);

                            $days = $diff->d;
                            $hours = $diff->h;
                            $minutes = $diff->i;

                            $timeRemaining = $days . ' days ' . $hours . ' hours ' . $minutes . ' minutes';
                        } else {
                            $timeRemaining = 'Session started or completed';
                        }
                    @endphp

                    <div style="background:white; border:1px solid #d1d5db; border-radius:14px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                        <h3 style="font-size:24px; font-weight:700; margin-bottom:12px; color:#111827;">
                            {{ $session->tutorProfile->user->name }}
                        </h3>

                        <p><strong>Student:</strong> {{ $session->student->name }}</p>
                        <p><strong>Date:</strong> {{ $session->session_date }}</p>
                        <p><strong>Time:</strong> {{ $session->session_time }}</p>
                        <p><strong>Location:</strong> {{ $session->meeting_location }}</p>
                        <p><strong>Status:</strong> {{ $session->status }}</p>

                        <div style="background:#e0f2fe; color:#075985; padding:12px 14px; border-radius:10px; margin-top:14px; font-weight:600;">
                            Time Remaining: {{ $timeRemaining }}
                        </div>

                        <iframe
                            width="100%"
                            height="260"
                            style="border:0; margin-top:16px; border-radius:10px;"
                            loading="lazy"
                            allowfullscreen
                            src="https://www.google.com/maps?q={{ urlencode($session->meeting_location) }}&output=embed">
                        </iframe>
                    </div>
                @empty
                    <div style="background:white; border:1px solid #d1d5db; border-radius:14px; padding:24px;">
                        No tutoring sessions booked yet.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>