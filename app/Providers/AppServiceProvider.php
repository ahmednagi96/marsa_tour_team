<?php

namespace App\Providers;

use App\Infrastructure\Payment\PaymentInterface;
use App\Models\TourAvailability;
use App\Models\User;
use App\Services\Interfaces\SendSmsInterface;
use App\Services\OTPService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SendSmsInterface::class,OTPService::class);

        $provider=config("payment.payment_providers.".config("payment.provider"));
        $this->app->bind(PaymentInterface::class,$provider);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
