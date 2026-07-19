<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Resources\BannerResource;
use App\Filament\Resources\BlogCategoryResource;
use App\Filament\Resources\BlogResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CouponResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ReviewResource;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\SupplierResource;
use App\Filament\Resources\NavigationItemResource;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\AdminWelcome;
use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\LowStockAlert;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatsOverview;
use App\Http\Middleware\SetLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id("admin")
            ->path("admin")
            ->login()
            ->colors(["primary" => Color::hex("#2D6A4F")])
            ->brandName(\App\Models\Setting::get('site_name', 'Garikothay') . ' Admin')
            ->authGuard("admin")
            ->userMenuItems([
                MenuItem::make()
                    ->label("English")
                    ->icon("heroicon-o-language")
                    ->url("/language/en"),
                MenuItem::make()
                    ->label("বাংলা")
                    ->icon("heroicon-o-language")
                    ->url("/language/bn"),
            ])
            ->navigationGroups([
                NavigationGroup::make("Catalog"),
                NavigationGroup::make("Orders"),
                NavigationGroup::make("Marketing"),
                NavigationGroup::make("Content"),
                NavigationGroup::make("Users"),
                NavigationGroup::make("Settings"),
                NavigationGroup::make("Site Management"),
                NavigationGroup::make("Theme"),
            ])
            ->resources([
                ProductResource::class,
                CategoryResource::class,
                OrderResource::class,
                CustomerResource::class,
                CouponResource::class,
                ReviewResource::class,
                BlogResource::class,
                BlogCategoryResource::class,
                BannerResource::class,
                PageResource::class,
                NavigationItemResource::class,
                SupplierResource::class,
                \App\Filament\Resources\CartResource::class,
                \App\Filament\Resources\InvoiceResource::class,
            ])
            ->pages([Dashboard::class, \App\Filament\Pages\Settings::class, \App\Filament\Pages\ThemeSettings::class])
            ->widgets([
                AdminWelcome::class,
                StatsOverview::class,
                RevenueChart::class,
                LatestOrdersTable::class,
                LowStockAlert::class,
                \App\Filament\Widgets\TopSellingProducts::class,
                \App\Filament\Widgets\OutOfStockProducts::class,
                \App\Filament\Widgets\TopSuppliers::class,
                \App\Filament\Widgets\CouponPerformance::class,
                \App\Filament\Widgets\CustomerAnalytics::class,
                \App\Filament\Widgets\RevenueAnalytics::class,
                \App\Filament\Widgets\RecentActivities::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => '<style>
                    html .trix-content b, html .trix-content strong { font-weight: 700; }
                    .fi-ta-content { max-height: 75vh !important; overflow-y: auto !important; }
                    .fi-ta-header-cell { position: sticky !important; top: 0 !important; z-index: 10 !important; }
                    html:not(.dark) .fi-ta-header-cell { background-color: rgb(255 255 255) !important; }
                    .dark .fi-ta-header-cell { background-color: rgb(24 24 27) !important; }
                    .trix-content p, .prose p, .fi-fo-rich-editor-content p { margin-top: 0.25em !important; margin-bottom: 0.25em !important; }
                </style>'
            );
    }
}
