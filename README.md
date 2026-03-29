# F11 Tutor Profile Module (Laravel MVC)

This module implements **F11: Tutor profile (subjects, rate, availability, ratings)** for your **Campus Connect Pro** project using the stack required in your files: **Laravel + Eloquent ORM + MySQL + Blade + Vanilla JS + Tailwind CSS + authentication with role-based middleware**.

## What is included

- Tutor profile creation and update for authenticated tutors
- Multiple subject expertise selection
- Hourly rate or **free** tutoring option
- Weekly availability slots (calendar-style schedule)
- Public tutor listing and profile details page
- Student ratings and written reviews
- Search/filter support on tutor listing page
- MVC structure with migrations, models, requests, controllers, routes, middleware, and Blade views

## What is intentionally NOT included yet

These belong to other features and are not implemented here:

- F12 session booking system
- F13 Google Maps navigation
- F14 SMS reminders
- F15 doubt forum

## Assumptions

1. Your project already has Laravel authentication working.
2. Your `users` table has at least `id`, `name`, `email`, and `role` columns.
3. The role values include at least `tutor`, `student`, and optionally `admin`.
4. Your app has a shared Blade layout file at `resources/views/layouts/app.blade.php`.
   - If your layout file has a different name, only change the `@extends('layouts.app')` line in the Blade files.

## Installation steps

### 1) Copy the files into your Laravel project
Copy each folder into the matching place inside your Laravel app:

- `app/...`
- `database/...`
- `resources/...`
- `routes/web.php` snippet into your real `routes/web.php`

### 2) Add the User model relations
Add the methods from `integration-snippets/User.php.relations.snippet.php` into your real `app/Models/User.php`.

### 3) Register the role middleware alias
If you already have a `role` middleware alias, keep yours.
If not, use the included `EnsureRole.php` and register it:

#### Laravel 11 (`bootstrap/app.php`)
```php
->withMiddleware(function ($middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureRole::class,
    ]);
})
```

#### Laravel 10 (`app/Http/Kernel.php`)
```php
protected $middlewareAliases = [
    // ...
    'role' => \App\Http\Middleware\EnsureRole::class,
];
```

### 4) Run migrations and seed subjects
```bash
php artisan migrate
php artisan db:seed --class=SubjectSeeder
```

### 5) Add navigation links if needed
Example:
- `/tutors`
- `/tutors/create`

## Ratings logic note
Right now students can rate tutors directly so the profile can show real ratings.
When you implement **F12 booking**, you should tighten this so only students with a **completed tutoring session** can submit a rating.

## Suggested future upgrade for F12
When session booking is ready:
- add `session_booking_id` to `tutor_reviews`
- allow review only if booking status is `completed`
- prevent duplicate review per completed session

## Files included

- Controllers
  - `TutorProfileController.php`
  - `TutorReviewController.php`
- Requests
  - `StoreTutorProfileRequest.php`
  - `StoreTutorReviewRequest.php`
- Models
  - `TutorProfile.php`
  - `Subject.php`
  - `TutorAvailabilitySlot.php`
  - `TutorReview.php`
- Middleware
  - `EnsureRole.php`
- Migrations
  - `create_subjects_table`
  - `create_tutor_profiles_table`
  - `create_subject_tutor_profile_table`
  - `create_tutor_availability_slots_table`
  - `create_tutor_reviews_table`
- Seeder
  - `SubjectSeeder.php`
- Views
  - `tutors/index.blade.php`
  - `tutors/show.blade.php`
  - `tutors/create.blade.php`
  - `tutors/edit.blade.php`
  - `tutors/partials/form.blade.php`
  - `tutors/partials/availability-row.blade.php`
- Route snippet
  - `routes/web.php`
- Integration snippet
  - `integration-snippets/User.php.relations.snippet.php`
