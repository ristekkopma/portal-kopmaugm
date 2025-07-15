<?php

namespace App\Providers\Filament;

use App\Enums\UserRole;
use Filament\Panel;

use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Portal\Pages;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\View\View;

class PortalPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('portal')
            ->path('portal')
            ->login()
            ->registration(Pages\Auth\Register::class)
            ->passwordReset()
            ->emailVerification()
            ->profile(Pages\Auth\Profile::class, false)
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->topNavigation()
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Portal/Resources'), for: 'App\\Filament\\Portal\\Resources')
            ->discoverPages(in: app_path('Filament/Portal/Pages'), for: 'App\\Filament\\Portal\\Pages')
            ->pages([
                Pages\Dashboard::class,
                Pages\Poin::class, // ← ini
            ])
            ->discoverWidgets(in: app_path('Filament/Portal/Widgets'), for: 'App\\Filament\\Portal\\Widgets')
            ->widgets([
                    \App\Filament\Widgets\MemberStats::class,

            ])
            ->brandLogo(asset('images/kopma-brand.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/logo.png'))
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('Administration'))
                    ->url(fn(): string => route('filament.admin.pages.dashboard'))
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn(): bool => auth()->user()->role !== UserRole::Candidate && auth()->user()->role !== UserRole::Member),
                // ...
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

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): View => view('filament.support-contact-button'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn(): View => view('filament.support-contact-button'),
        );
    }
}
