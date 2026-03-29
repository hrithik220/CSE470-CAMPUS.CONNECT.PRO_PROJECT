@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Create tutor profile</h1>
            <p class="mt-2 text-slate-600">Build your academic profile so students can discover your subjects, rates, and availability.</p>
        </div>

        <form action="{{ route('tutors.store') }}" method="POST" class="space-y-8">
            @csrf
            @include('tutors.partials.form', ['subjects' => $subjects])
        </form>
    </div>
@endsection
