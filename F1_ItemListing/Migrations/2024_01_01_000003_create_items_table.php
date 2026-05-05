<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('category', [
                'textbooks',
                'electronics',
                'furniture',
                'clothing',
                'sports',
                'supplies',
                'tickets',
                'other'
            ]);
            $table->enum('condition', ['new', 'used', 'fair']);
            $table->enum('status', ['available', 'sold', 'reserved', 'flagged'])->default('available');
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'status']);
            $table->index(['price']);
            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
