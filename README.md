# Campus Connect Pro

A unified campus lifestyle platform for university students.

## Team Members & Modules
| Member   | Module                    |
|----------|---------------------------|
| Hrithik  | Marketplace               |
| Ramisha  | Karma & Sustainability    |
| Nahid    | Tutoring + Academic       |
| Pronoy   | Ride Sharing              |

## Tech Stack
- **Backend**: Laravel (PHP) + MySQL
- **Frontend**: Blade Templates + Tailwind CSS + Vanilla JS
- **APIs**: Google Maps API, SMS API
- **Auth**: Laravel Auth with Middleware Role Management

## Sprint Progress
- [x] Sprint 1 — Project Proposal & SRS
- [x] Sprint 2 — Class Diagram & Initial Structure
- [ ] Sprint 3 — Feature Implementation
- [ ] Sprint 4 — Final Polish & Deployment

## Setup Instructions
```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/campus-connect-pro.git
cd campus-connect-pro

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_DATABASE=campus_connect_pro
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Link storage
php artisan storage:link

# 7. Start server
php artisan serve
```

## Feature Checklist

### Hrithik — Marketplace
- [x] F1: Item listing (photo, price, condition, category)
- [x] F2: In-app chat (buyer-seller negotiation)
- [ ] F3: Seller credibility score display
- [ ] F4: Buyer review & rating system
- [ ] F5: Item search & filter

### Ramisha — Karma & Sustainability
- [ ] F6: Karma points award system
- [ ] F7: Monthly leaderboard + badges
- [ ] F8: Fraud detection → admin panel
- [ ] F9: Sustainability dashboard
- [ ] F10: Deadline tracker + notifications

### Nahid — Tutoring + Academic
- [ ] F11: Tutor profile
- [ ] F12: Session booking
- [ ] F13: Google Maps campus navigation
- [ ] F14: SMS reminder
- [ ] F15: Doubt forum

### Pronoy — Ride Sharing
- [ ] F16: Ride offer post
- [ ] F17: Ride request panel
- [ ] F18: SMS notification
- [ ] F19: Ride history dashboard
- [ ] F20: Ride search & filter
