# Campus Connect Pro — F6 & F7 Feature Package

## F6 — Karma Points Award System
## F7 — Carbon Footprint Calculator for Rides

---

## 📁 File Structure

```
campus-connect/
├── app/
│   ├── Http/Controllers/
│   │   ├── KarmaController.php          ← F6 controller
│   │   └── CarbonFootprintController.php ← F7 controller
│   └── Models/
│       ├── KarmaPoint.php               ← F6 model + action map + badge logic
│       └── CarbonFootprintLog.php        ← F7 model + CO₂ calculation logic
├── database/migrations/
│   ├── ..._create_karma_points_table.php
│   └── ..._create_carbon_footprint_logs_table.php
├── resources/views/
│   ├── karma/
│   │   ├── index.blade.php              ← F6 dashboard
│   │   └── leaderboard.blade.php        ← F6 leaderboard
│   └── carbon/
│       └── index.blade.php              ← F7 calculator + history
└── routes/
    └── f6_f7_routes.php                 ← All routes for F6 & F7
```

---

## ⚙️ Setup Instructions

### 1. Copy files into your Laravel project
Place each file into the matching path in your Laravel root.

### 2. Register routes
In your `routes/web.php`, add at the bottom:
```php
require __DIR__.'/f6_f7_routes.php';
```

### 3. Run migrations
```bash
php artisan migrate
```
This will:
- Create `karma_points` table
- Create `carbon_footprint_logs` table
- Add `karma_total`, `karma_badge`, `co2_saved_total` columns to the `users` table

### 4. Ensure your `layouts/app` Blade layout supports:
- `@stack('styles')` inside `<head>`
- `@stack('scripts')` before `</body>`
- Tailwind CSS loaded

---

## 🎯 F6 — Karma System Features

| Feature | Detail |
|---------|--------|
| Points per action | Defined in `KarmaPoint::ACTION_MAP` |
| Badges | Newcomer → Member → Contributor → Helper → Trusted → Champion → Legend |
| Dashboard | Personal history, per-module breakdown, badge progress bar |
| Leaderboard | Top 20 users, your rank highlighted |
| Integration | Call `KarmaController::award($userId, 'action', 'module')` from any controller |

### Award karma from another controller (example)
```php
use App\Http\Controllers\KarmaController;

// After a ride is completed:
KarmaController::award($ride->driver_id, 'ride_completed_driver', 'rides', $ride->id, Ride::class);
KarmaController::award($ride->rider_id,  'ride_completed_rider',  'rides', $ride->id, Ride::class);
```

---

## 🌿 F7 — Carbon Footprint Features

| Feature | Detail |
|---------|--------|
| Vehicle types | Car, Motorcycle, CNG/Auto, Bus, Bicycle, Walking |
| Emission factors | IPCC/Our World in Data averages (kg CO₂/km) |
| CO₂ saved | Compared against baseline solo car journey |
| Live preview | AJAX debounced calculation before form submit |
| Trees equivalent | CO₂ saved ÷ 21 kg/year per tree |
| Campus total | Aggregate saved CO₂ and trip count across all users |
| Karma integration | Automatically awards karma points on trip log |

### Log carbon automatically after ride completion (example)
```php
use App\Http\Controllers\CarbonFootprintController;

// In RideController@complete:
CarbonFootprintController::logRideCompletion(
    userId:      $ride->driver_id,
    rideId:      $ride->id,
    vehicleType: $ride->vehicle_type,  // 'car','motorcycle','cng' etc.
    distanceKm:  $ride->distance_km,
    passengers:  $ride->passenger_count,
    isShared:    true
);
```

---

## 🔗 Routes Summary

| Route | Name | Description |
|-------|------|-------------|
| GET  /karma | karma.index | Personal karma dashboard |
| GET  /karma/leaderboard | karma.leaderboard | Top 20 leaderboard |
| GET  /carbon | carbon.index | Calculator + trip history |
| POST /carbon/preview | carbon.preview | AJAX live CO₂ preview |
| POST /carbon | carbon.store | Save a logged trip |

All routes require `auth` middleware.
