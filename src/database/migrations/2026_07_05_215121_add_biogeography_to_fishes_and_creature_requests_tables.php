<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('fishes', 'biogeography')) {
            Schema::table('fishes', function (Blueprint $table) {
                $table->string('biogeography', 50)
                    ->nullable()
                    ->after('conservation_status');
            });
        }

        if (! Schema::hasColumn('creature_requests', 'biogeography')) {
            Schema::table('creature_requests', function (Blueprint $table) {
                $table->string('biogeography', 50)
                    ->nullable()
                    ->after('conservation_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('creature_requests', 'biogeography')) {
            Schema::table('creature_requests', function (Blueprint $table) {
                $table->dropColumn('biogeography');
            });
        }

        if (Schema::hasColumn('fishes', 'biogeography')) {
            Schema::table('fishes', function (Blueprint $table) {
                $table->dropColumn('biogeography');
            });
        }
    }
};
