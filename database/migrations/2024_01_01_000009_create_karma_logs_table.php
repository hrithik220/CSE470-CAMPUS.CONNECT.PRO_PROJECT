<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karma_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points');
            $table->enum('action', [
                'sale_completed',
                'review_received',
                'good_rating',
                'badge_earned',
                'admin_adjustment',
                'fraud_penalty'
            ]);
            $table->string('description');
            $table->morphs('reference'); // reference_id + reference_type for polymorphic
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karma_logs');
    }
};
