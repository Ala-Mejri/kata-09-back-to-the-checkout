<?php

declare(strict_types=1);

namespace App\Providers;

use App\Module\PricingRule\Service\PricingRulesBuilderInterface;
use App\Module\PricingRule\Service\SpringSalePricingRulesBuilder;
use App\Shared\Response\JsonResponse;
use App\Shared\Response\ResponseInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ResponseInterface::class, JsonResponse::class);
        $this->app->bind(PricingRulesBuilderInterface::class, SpringSalePricingRulesBuilder::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
