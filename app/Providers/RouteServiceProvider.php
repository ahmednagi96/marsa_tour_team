<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $this->routes(function () {
            Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api/v1')
                ->name('v1.')
                ->group(base_path('routes/api/v1/travel.php'));

                Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api/v1')
                ->name('v1.')
                ->group(base_path('routes/api/v1/booking.php'));

                
                Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api/v1')
                ->name('v1.')
                ->group(base_path('routes/api/v1/payment.php'));
                  
                Route::middleware(['api', 'localizationRedirect', 'localeViewPath'])
                ->prefix(LaravelLocalization::setLocale() . '/api/auth')
                ->name('auth.')
                ->group(base_path('routes/api/auth.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            if ($request->is('*api/v1/booking*')) {
                 return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
            }else{
                return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            }
        });
    }
}
