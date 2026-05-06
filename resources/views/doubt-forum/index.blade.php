<x-app-layout>
    <div style="background:#f3f4f6; min-height:100vh; padding:40px 0;">
        <div style="max-width:1100px; margin:0 auto; padding:0 24px;">

            <h2 style="font-size:32px; font-weight:700; margin-bottom:24px;">
                Doubt Forum
            </h2>

            @if(session('success'))
                <div style="background:#dcfce7; padding:12px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- POST QUESTION -->
            <form method="POST" action="/doubt-forum/question">
                @csrf

                <input name="course_code" placeholder="Course Code"
                       style="width:100%; padding:10px; margin-bottom:10px;">

                <textarea name="question" placeholder="Your question"
                          style="width:100%; padding:10px;"></textarea>

                <label>
                    <input type="checkbox" name="is_anonymous" checked>
                    Anonymous
                </label>

                <br><br>

                <button style="background:#16a34a; color:white; padding:10px;">
                    Post Question
                </button>
            </form>

            <hr><br>

            <!-- QUESTIONS -->
            @foreach($questions as $q)
                <div style="background:white; padding:20px; margin-bottom:20px;">

                    <h3>{{ $q->course_code }}</h3>
                    <p>{{ $q->question }}</p>

                    <p>
                        {{ $q->is_anonymous ? 'Anonymous' : $q->user->name }}
                    </p>

                    <!-- ANSWERS -->
                    @foreach($q->answers as $a)
                        <div style="margin-top:10px; border-top:1px solid #ccc; padding-top:10px;">
                            <p>{{ $a->answer }}</p>
                            <p>Votes: {{ $a->votes }}</p>

                            <form method="POST" action="/doubt-forum/upvote/{{ $a->id }}">
                                @csrf
                                <button>👍</button>
                            </form>

                            <form method="POST" action="/doubt-forum/downvote/{{ $a->id }}">
                                @csrf
                                <button>👎</button>
                            </form>
                        </div>
                    @endforeach

                    <!-- ADD ANSWER -->
                    <form method="POST" action="/doubt-forum/answer/{{ $q->id }}">
                        @csrf
                        <textarea name="answer" placeholder="Answer"></textarea>
                        <button>Submit</button>
                    </form>

                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>