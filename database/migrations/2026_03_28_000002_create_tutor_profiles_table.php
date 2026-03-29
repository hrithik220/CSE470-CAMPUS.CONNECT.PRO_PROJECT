<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->string('headline', 120)->nullable();
            $table->text('bio');
            $table->boolean('is_free')->default(false)->index();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
