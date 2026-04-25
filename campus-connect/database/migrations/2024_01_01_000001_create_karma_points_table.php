<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karma_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);         // positive or negative delta
            $table->string('action');                       // e.g. 'ride_shared', 'item_sold'
            $table->string('module');                       // 'rides','marketplace','tutoring','community'
            $table->text('description')->nullable();
            $table->foreignId('reference_id')->nullable();  // polymorphic reference (ride_id, listing_id…)
            $table->string('reference_type')->nullable();
            $table->timestamps();
        });

        // Running total cached on users table (add column if not exists)
        if (!Schema::hasColumn('users', 'karma_total')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('karma_total')->default(0)->after('email');
                $table->string('karma_badge')->default('Newcomer')->after('karma_total');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('karma_points');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['karma_total', 'karma_badge']);
        });
    }
};
