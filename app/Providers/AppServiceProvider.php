<?php

namespace App\Providers;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

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
