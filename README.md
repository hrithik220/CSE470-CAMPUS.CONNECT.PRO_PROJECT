# 🏫 Campus Connect Pro

> A production-ready campus-based marketplace and sustainability platform built with Laravel 10+

![Laravel](https://img.shields.io/badge/Laravel-10+-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Project Structure](#project-structure)
- [Usage](#usage)
- [Demo Credentials](#demo-credentials)

---

## ✨ Features

### 🔐 Authentication System
- Student Registration with university email validation
- Login / Logout with remember me
- Password reset via email
- Role-based access control (student / admin)
- Account suspension system

### 🛍️ Marketplace Module
- **Item Listings** — Create, edit, delete listings with multiple image upload
- **Search & Filter** — By keyword, category, condition, price range with sorting
- **In-App Chat** — Real-time AJAX messaging between buyer and seller
- **Transaction System** — Initiate, confirm, and cancel purchases
- **Review System** — 1-5 star ratings with comments after purchase
- **Seller Profiles** — Karma score, sales count, average rating, transaction history

### ⚡ Karma & Sustainability Module
- **Karma Points** — +10 per sale, +1 to +5 per review rating received
- **Leaderboard** — Monthly and all-time rankings
- **Badges** — Rising Star (25), Trusted Member (50), Top Seller (100), Eco Warrior (150), Campus Hero (200)
- **Sustainability Dashboard** — CO₂ saved, items reused, money saved with Chart.js graphs
- **Notification System** — Database + email notifications for messages, reviews, karma updates

### 🛡️ Admin Panel
- User management (search, filter, suspend/unsuspend)
- Fraud detection (fake reviews, spam listings, karma spikes)
- Automated fraud scanning
- Marketplace monitoring with item flagging
- Sustainability statistics overview

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 10+, PHP 8.1+ |
| Frontend | Blade Templates, Tailwind CSS (CDN) |
| Database | MySQL / SQLite |
| Charts | Chart.js 4 |
| Auth | Laravel Built-in Auth |
| API | Laravel Sanctum |
| Notifications | Laravel Notifications (DB + Mail) |

---

## 🚀 Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+ (or SQLite)
- Node.js & NPM (optional, for asset compilation)

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone <repository-url> campus-connect-pro
cd campus-connect-pro

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
#    For MySQL:
#      DB_CONNECTION=mysql
#      DB_DATABASE=campus_connect_pro
#      DB_USERNAME=root
#      DB_PASSWORD=your_password
#
#    For SQLite (quick setup):
#      DB_CONNECTION=sqlite
#      DB_DATABASE=/full/path/to/database.sqlite
#      Then run: touch database/database.sqlite

# 6. Run migrations
php artisan migrate

# 7. Seed with demo data
php artisan db:seed

# 8. Create storage symlink (for image uploads)
php artisan storage:link

# 9. Start the development server
php artisan serve
```

Visit `http://localhost:8000` to access the application.

---

## 🗄️ Database Schema

```
users                  items                  item_images
├── id                 ├── id                 ├── id
├── name               ├── seller_id (FK)     ├── item_id (FK)
├── email              ├── title              ├── image_path
├── password           ├── description        └── sort_order
├── role               ├── price
├── university_id      ├── category           conversations
├── karma_points       ├── condition           ├── id
├── avatar             ├── status              ├── item_id (FK)
├── bio                ├── views_count         ├── buyer_id (FK)
├── is_suspended       └── timestamps          ├── seller_id (FK)
└── timestamps                                 └── last_message_at

messages               transactions           reviews
├── id                 ├── id                 ├── id
├── conversation_id    ├── item_id (FK)       ├── transaction_id (FK)
├── sender_id (FK)     ├── buyer_id (FK)      ├── reviewer_id (FK)
├── body               ├── seller_id (FK)     ├── reviewed_user_id (FK)
├── is_read            ├── amount             ├── item_id (FK)
└── timestamps         ├── status             ├── rating (1-5)
                       └── timestamps         ├── comment
                                              └── timestamps

karma_logs             badges                 badge_user
├── id                 ├── id                 ├── user_id (FK)
├── user_id (FK)       ├── name               ├── badge_id (FK)
├── points             ├── slug               └── earned_at
├── action             ├── description
├── description        ├── icon               fraud_reports
├── reference_id       ├── color              ├── id
├── reference_type     ├── karma_threshold    ├── reported_user_id (FK)
└── timestamps         └── timestamps         ├── reported_by (FK)
                                              ├── type
notifications                                 ├── reason
├── id (UUID)                                 ├── status
├── type                                      ├── admin_notes
├── notifiable_id                             ├── resolved_by (FK)
├── notifiable_type                           ├── resolved_at
├── data                                      └── timestamps
├── read_at
└── timestamps
```

---

## 📁 Project Structure

```
campus-connect-pro/
├── app/
│   ├── Console/Kernel.php
│   ├── Exceptions/Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          (Login, Register, Password Reset)
│   │   │   ├── Marketplace/   (Items, Chat, Reviews, Transactions)
│   │   │   ├── Karma/         (Karma, Leaderboard, Sustainability)
│   │   │   ├── Admin/         (Dashboard, Users, Fraud, Marketplace)
│   │   │   ├── DashboardController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/        (Admin, Role, Suspended, Auth)
│   │   ├── Requests/          (Form validation classes)
│   │   └── Kernel.php
│   ├── Models/                (User, Item, Message, Review, etc.)
│   ├── Notifications/         (Message, Review, Karma notifications)
│   ├── Providers/             (App, Auth, Event, Route)
│   └── Services/              (Karma, Fraud Detection, Sustainability)
├── database/
│   ├── migrations/            (12 migration files)
│   └── seeders/               (Users, Badges, Items with demo data)
├── resources/views/
│   ├── layouts/               (app.blade.php, guest.blade.php)
│   ├── auth/                  (login, register, forgot/reset password)
│   ├── marketplace/           (index, show, create, edit, chat, etc.)
│   ├── karma/                 (index, leaderboard, sustainability)
│   ├── admin/                 (dashboard, users, fraud, marketplace)
│   ├── profile/               (show, edit)
│   ├── dashboard.blade.php
│   └── welcome.blade.php
├── routes/
│   ├── web.php                (All web routes)
│   └── api.php                (API routes)
└── config/                    (app, auth, database, session, etc.)
```

---

## 👤 Demo Credentials

After running `php artisan db:seed`:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@university.edu | password |
| **Student** | hrithik@university.edu | password |
| **Student** | ramisha@university.edu | password |
| **Student** | alice@university.edu | password |

All demo students use `password` as their password.

---

## 🎨 UI Design

- **Dark theme** with glassmorphism effects
- **Responsive** sidebar navigation
- **Card-based** item display with hover animations
- **Messenger-style** chat UI with AJAX real-time updates
- **Chart.js** powered sustainability dashboard
- **Inter** font family from Google Fonts

---

## 📄 License

MIT License — Built for educational purposes.
