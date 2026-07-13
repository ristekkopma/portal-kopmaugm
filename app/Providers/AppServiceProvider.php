<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->role === UserRole::SuperAdmin) {
                return true;
            }
        });

        Gate::define('view_event_followers', fn (User $user): bool => $user->role === UserRole::Admin && $user->can_manage_event_followers
        );

        Gate::define('manage_event_followers', fn (User $user): bool => $user->role === UserRole::Admin && $user->can_manage_event_followers
        );

        Gate::define('export_event_followers', fn (User $user): bool => $user->role === UserRole::Admin && $user->can_manage_event_followers
        );

        Gate::define('send_event_notifications', fn (User $user): bool => $user->role === UserRole::Admin && $user->can_send_event_notifications
        );

        Gate::define('view_event_notification_batches', fn (User $user): bool => $user->role === UserRole::Admin && $user->can_send_event_notifications
        );

        Gate::define('retry_event_notifications', fn (User $user): bool => $user->role === UserRole::Admin && $user->can_send_event_notifications
        );

        Http::macro('waha', function () {
            $base = config('waha.base_url');
            $timeout = config('waha.timeout');
            $apiKey = config('waha.api_key');

            $req = Http::baseUrl($base)->timeout($timeout);
            if ($apiKey) {
                $req = $req->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                ]);
            }

            return $req;
        });
    }
}
