<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fishes', function (Blueprint $table) {
            $table->string('conservation_status', 50)
                ->nullable()
                ->after('food')
                ->index();
        });

        Schema::table('creature_requests', function (Blueprint $table) {
            $table->string('conservation_status', 50)
                ->nullable()
                ->after('food')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('creature_requests', function (Blueprint $table) {
            $table->dropColumn('conservation_status');
        });

        Schema::table('fishes', function (Blueprint $table) {
            $table->dropColumn('conservation_status');
        });
    }
};
