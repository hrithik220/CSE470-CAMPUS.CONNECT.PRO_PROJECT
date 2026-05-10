CAMPUS CONNECT PRO — COMPLETE 20 FEATURE VERSION

This ZIP is rebuilt as a clean Laravel project root. It uses SQLite by default, so you do NOT need XAMPP MySQL or phpMyAdmin.

FEATURE COVERAGE
F1-F5   Marketplace: item listing, chat, seller credibility/transaction history, reviews, search/filter
F6-F10  Karma + sustainability: karma points, leaderboard/badges, fraud admin, sustainability, notification/deadline-style reminders
F11-F15 Tutoring + academic: tutor profiles, session booking, Google Maps embed, SMS reminder simulation/countdown, doubt forum with upvote/downvote
F16-F20 Ride sharing: ride offer, ride join request panel, SMS notification simulation, ride history, ride search/filter

HOW TO RUN
1. Extract the ZIP.
2. Open terminal in this exact folder, where artisan and composer.json are visible.
3. Run:
   composer install
   php artisan key:generate
   php artisan migrate:fresh
   php artisan serve
4. Open:
   http://127.0.0.1:8000

IMPORTANT
- Do not use MySQL unless your teacher specifically requires it. SQLite is already configured.
- If you want MySQL later, change .env DB_CONNECTION=mysql and create the database manually.
- If you get 'Could not open input file: artisan', you are in the wrong folder. Open the folder where artisan exists.

MAIN ROUTES AFTER LOGIN
/dashboard
/marketplace
/rides
/tutors
/tutoring-sessions
/doubt-forum
/karma
/karma/leaderboard
/karma/sustainability
/admin
