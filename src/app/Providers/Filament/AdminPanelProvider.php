<?php

namespace App\Providers\Filament;

use App\Models\User;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->authGuard('admin')
            ->authPasswordBroker('users')
            ->brandName('freshwater_creatures')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->maxContentWidth(
                MaxWidth::SevenExtraLarge
            )
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path(
                    'Filament/Admin/Resources'
                ),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path(
                    'Filament/Admin/Pages'
                ),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverClusters(
                in: app_path(
                    'Filament/Admin/Clusters'
                ),
                for: 'App\\Filament\\Admin\\Clusters'
            )
            ->discoverWidgets(
                in: app_path(
                    'Filament/Admin/Widgets'
                ),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Administration'),

                NavigationGroup::make()
                    ->label('Master Data'),

                NavigationGroup::make()
                    ->label('Pengaturan'),

                NavigationGroup::make()
                    ->label('Konten'),

                NavigationGroup::make()
                    ->label('Manajemen Data'),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(function (): string {
                        $user = Filament::auth()->user();

                        return $user instanceof User
                            ? $user->name
                            : 'Profil';
                    })
                    ->url(
                        fn (): string =>
                            Filament::getProfileUrl()
                            ?? url('/admin')
                    )
                    ->icon('heroicon-m-user-circle'),
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'lg' => 3,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 2,
                        'lg' => 3,
                    ]),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}