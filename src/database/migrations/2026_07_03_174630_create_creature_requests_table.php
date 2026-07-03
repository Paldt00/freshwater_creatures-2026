<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creature_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('fish_id')
                ->nullable()
                ->constrained('fishes')
                ->nullOnDelete();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->enum('request_type', ['add', 'update'])->default('add');
            $table->enum('request_status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->string('name');
            $table->string('scientific_name')->nullable();
            $table->string('image')->nullable();
            $table->string('habitat')->nullable();
            $table->string('food')->nullable();
            $table->longText('characteristics')->nullable();
            $table->longText('description')->nullable();

            $table->text('request_note')->nullable();
            $table->text('admin_note')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['request_type', 'request_status']);
            $table->index(['user_id', 'request_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creature_requests');
    }
};
