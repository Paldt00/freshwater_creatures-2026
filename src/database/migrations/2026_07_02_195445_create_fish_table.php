<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fishes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('scientific_name')->nullable();
            $table->string('image')->nullable();
            $table->string('habitat')->nullable();
            $table->string('food')->nullable();
            $table->longText('characteristics')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['region_id', 'category_id']);
            $table->index(['is_featured', 'is_published']);
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fishes');
    }
};
