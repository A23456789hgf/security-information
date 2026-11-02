<?php

namespace App\Providers;

use App\Models\Section;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Share dynamic sections and pages with the sidebar
        view()->composer(['layouts.app', 'layouts.*', '*'], function ($view) {
            if (Schema::hasTable('sections') && Schema::hasTable('dynamic_pages')) {
                $sections = Cache::remember('sidebar_dynamic_sections', 86400, function () {
                    return Section::with(['pages' => function ($q) {
                        $q->where('is_active', true)->orderBy('name');
                    }])
                    ->where('is_active', true)
                    ->orderBy('order_column')
                    ->orderBy('name')
                    ->get();
                });
                $view->with('sidebarDynamicSections', $sections);
            } else {
                $view->with('sidebarDynamicSections', collect());
            }
        });
    }
}
