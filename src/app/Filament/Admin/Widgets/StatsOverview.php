<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Category;
use App\Models\CreatureRequest;
use App\Models\Fish;
use App\Models\Region;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalFish = Fish::query()->count();

        $publishedFish = Fish::query()
            ->where('is_published', true)
            ->count();

        $totalRegions = Region::query()->count();

        $totalCategories = Category::query()->count();

        $totalUsers = User::query()
            ->where('is_admin', false)
            ->count();

        $pendingRequests = CreatureRequest::query()
            ->where('request_status', 'pending')
            ->count();

        $approvedRequests = CreatureRequest::query()
            ->where('request_status', 'approved')
            ->count();

        $rejectedRequests = CreatureRequest::query()
            ->where('request_status', 'rejected')
            ->count();

        return [
            Stat::make('Total Ikan', number_format($totalFish))
                ->description($publishedFish . ' ikan sudah dipublikasikan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Wilayah', number_format($totalRegions))
                ->description('Wilayah persebaran ikan')
                ->descriptionIcon('heroicon-m-map')
                ->color('info'),

            Stat::make('Total Kategori', number_format($totalCategories))
                ->description('Kategori ikan air tawar')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),

            Stat::make('Pengguna', number_format($totalUsers))
                ->description('User biasa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),

            Stat::make('Request Pending', number_format($pendingRequests))
                ->description('Menunggu review admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Request Disetujui', number_format($approvedRequests))
                ->description('Request sudah diterapkan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Request Ditolak', number_format($rejectedRequests))
                ->description('Request ditolak admin')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
