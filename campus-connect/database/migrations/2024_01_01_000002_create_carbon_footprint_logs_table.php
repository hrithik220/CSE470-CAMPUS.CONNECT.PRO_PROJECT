<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carbon_footprint_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ride_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('vehicle_type', ['car', 'motorcycle', 'cng', 'bus', 'bicycle', 'walking']);
            $table->decimal('distance_km', 8, 2);
            $table->integer('passengers')->default(1);       // total occupants incl. driver
            $table->decimal('co2_saved_kg', 8, 4)->default(0); // vs solo private car
            $table->decimal('co2_emitted_kg', 8, 4)->default(0);
            $table->boolean('is_shared_ride')->default(false);
            $table->timestamps();
        });

        // Cumulative CO2 saved cached on users
        if (!Schema::hasColumn('users', 'co2_saved_total')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('co2_saved_total', 10, 4)->default(0)->after('karma_badge');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('carbon_footprint_logs');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('co2_saved_total');
        });
    }
};
