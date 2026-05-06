<x-app-layout>
    <div style="background:#f3f4f6; min-height:100vh; padding:40px 0;">
        <div style="max-width:1200px; margin:0 auto; padding:0 24px;">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                <h2 style="font-size:32px; font-weight:700; color:#111827; margin:0;">
                    All Tutors
                </h2>

                <div style="display:flex; gap:12px;">
                    <a href="/tutors/create"
                       style="background:#2563eb; color:white; padding:10px 18px; border-radius:10px; text-decoration:none; font-weight:600; display:inline-block;">
                        Add New Tutor
                    </a>

                    <a href="/tutoring-sessions"
                       style="background:#7c3aed; color:white; padding:10px 18px; border-radius:10px; text-decoration:none; font-weight:600; display:inline-block;">
                        View Sessions
                    </a>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr; gap:24px;">
                @foreach($tutors as $tutor)
                    <div style="background:white; border:1px solid #d1d5db; border-radius:14px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                        <h3 style="font-size:24px; font-weight:700; margin-bottom:12px; color:#111827;">
                            {{ $tutor->user->name }}
                        </h3>

                        <p><strong>Subjects:</strong> {{ $tutor->subjects }}</p>
                        <p><strong>Rate:</strong> {{ $tutor->is_free ? 'Free' : $tutor->hourly_rate }}</p>
                        <p><strong>Availability:</strong> {{ $tutor->availability }}</p>
                        <p><strong>Location:</strong> {{ $tutor->meeting_location }}</p>

                        <a href="/tutoring-sessions/create/{{ $tutor->id }}"
                           style="background:#16a34a; color:white; padding:10px 18px; border-radius:10px; text-decoration:none; font-weight:600; display:inline-block; margin-top:16px;">
                            Book Session
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>