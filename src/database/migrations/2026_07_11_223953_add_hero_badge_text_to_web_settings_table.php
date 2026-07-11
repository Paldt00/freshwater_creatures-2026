<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('web_settings')
            && ! Schema::hasColumn(
                'web_settings',
                'hero_badge_text'
            )
        ) {
            Schema::table(
                'web_settings',
                function (Blueprint $table): void {
                    $table
                        ->string('hero_badge_text')
                        ->nullable()
                        ->after('site_name');
                }
            );
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('web_settings')
            && Schema::hasColumn(
                'web_settings',
                'hero_badge_text'
            )
        ) {
            Schema::table(
                'web_settings',
                function (Blueprint $table): void {
                    $table->dropColumn(
                        'hero_badge_text'
                    );
                }
            );
        }
    }
};
