Campus Connect Pro
A unified campus lifestyle platform for university students built with Laravel.

Team Members & Modules
Hrithik Marketplace F1–F5 
Ramisha Karma & Sustainability F6–F10
Nahid Tutoring + Academic F11–F15 
Pronoy Ride Sharing F16–F20

Tech Stack

Backend: Laravel 13 (PHP 8.5) + MySQL
Frontend: Blade Templates + Tailwind CSS + Vanilla JS
APIs: Google Maps API, SMS API
Auth: Laravel Authentication with Middleware Role Management
Version Control: Git + GitHub

campus-connect-pro/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Hrithik/          ← Marketplace
│   │   │   ├── Ramisha/          ← Karma & Sustainability
│   │   │   ├── Nahid/            ← Tutoring + Academic
│   │   │   └── Pronoy/           ← Ride Sharing
│   │   └── Middleware/
│   └── Models/
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── marketplace/          ← Hrithik
│       ├── karma/                ← Ramisha
│       ├── tutoring/             ← Nahid
│       └── rides/                ← Pronoy
├── routes/
│   └── web.php
└── README.md

MVC Architecture
This project strictly follows the MVC (Model-View-Controller) pattern:

Models — Located in app/Models/, handle database interactions via Eloquent ORM
Views — Located in resources/views/, built with Laravel Blade templates
Controllers — Located in app/Http/Controllers/, handle business logic separated by member

Setup Instructions

# 1. Clone the repository
git clone https://github.com/hrithik220/CSE470-CAMPUS.CONNECT.PRO_PROJECT.git
cd CSE470-CAMPUS.CONNECT.PRO_PROJECT

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_connect_pro
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Link storage
php artisan storage:link

# 7. Start server
php artisan serve
