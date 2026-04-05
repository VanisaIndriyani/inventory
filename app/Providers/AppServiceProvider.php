<?php

namespace App\Providers;

use App\Models\Inventory;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $criticalItems = Inventory::all()->filter(fn ($inventory) => $inventory->status !== 'Aman');
                $view->with('globalCriticalItems', $criticalItems);
            }
        });
    }
}
