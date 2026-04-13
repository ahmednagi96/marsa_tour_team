<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends ServiceProvider
{
    use ApiResponse;
    public const HOME = '/home';
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureRateLimitingForBooking();

        $this->routes(function () {
            Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api/v1')
                ->name('v1.')
                ->group(base_path('routes/api/v1/travel.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function configureRateLimitingForBooking(): void
    {
        RateLimiter::for('bookings', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return $this->error(__('messages.too_many_requests'), 429);
                });
        });
    }
}
