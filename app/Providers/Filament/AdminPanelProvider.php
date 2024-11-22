<?php

namespace App\Providers\Filament;

use App\Filament\Pages;
use Filament\Actions;
use Filament\Forms;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Infolists;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Widgets;
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
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->collapsibleNavigationGroups(false)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->maxContentWidth(MaxWidth::Full)
            ->font('Onest')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                // 
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
            ])
            ->navigationGroups([]);
    }

    public function boot(): void
    {
        Tables\Actions\ActionGroup::configureUsing(fn(Tables\Actions\ActionGroup $group) => $group->color('white'));

        Tables\Actions\CreateAction::configureUsing(fn(Tables\Actions\CreateAction $action) => $action->modalWidth(MaxWidth::ExtraLarge));
        Tables\Actions\ViewAction::configureUsing(fn(Tables\Actions\ViewAction $action) => $action->modalWidth(MaxWidth::Large)->hiddenLabel());
        Tables\Actions\EditAction::configureUsing(fn(Tables\Actions\EditAction $action) => $action->modalWidth(MaxWidth::Large)->hiddenLabel()->color('gray'));

        Actions\CreateAction::configureUsing(fn(Actions\CreateAction $action) => $action->icon('heroicon-o-plus'));

        Forms\Components\DateTimePicker::configureUsing(fn(Forms\Components\DateTimePicker $picker) => $picker->seconds(false));
        Forms\Components\Select::configureUsing(fn(Forms\Components\Select $select) => $select->searchable());

        Forms\Components\Component::configureUsing(fn(Forms\Components\Component $component) => $component->translateLabel());
        Tables\Columns\Column::configureUsing(fn(Tables\Columns\Column $column) => $column->translateLabel());
        Tables\Columns\Column::configureUsing(fn(Tables\Columns\Column $column) => $column->placeholder('None')->translateLabel());
        Infolists\Components\Entry::configureUsing(fn(Infolists\Components\Entry $select) => $select->placeholder('Empty')->translateLabel());

        Tables\Table::configureUsing(
            fn(Tables\Table $table) => $table
                ->filtersLayout(FiltersLayout::AboveContent)
                ->paginationPageOptions([5, 10, 25, 50, 100])
        );
    }
}
