<?php

namespace App\Providers;

use Exception;
use App\Helper\ShaggoPartnerApiService;
use Illuminate\Support\ServiceProvider;
use App\Helper\BillerAggregationService;
use App\Contract\AirtimeBillingServiceInterface;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void {
    $this->app->singleton(
      AirtimeBillingServiceInterface::class,
      function ($app) {
        // You can use configuration or other mechanisms to determine the service

        switch (config('app.airtime_billing_service')) {
          case 'biller_aggregation':
            return new BillerAggregationService();
            
          case 'shaggo_partner_api':
            return new ShaggoPartnerApiService();
            
          default:
            throw new Exception('Invalid airtime billing service configuration');
        }
      }
    );
  }
}
