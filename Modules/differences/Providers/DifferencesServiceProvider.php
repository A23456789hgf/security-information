<?php

namespace Modules\Differences\Providers;

use Illuminate\Support\ServiceProvider;

class DifferencesServiceProvider extends ServiceProvider
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
        // 1. Load routes
        if (file_exists(__DIR__.'/../Routes/web.php')) {
            $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        }

        // 2. Load migrations
        if (is_dir(__DIR__.'/../Database/Migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        }

        // 3. Load views
        if (is_dir(__DIR__.'/../Resources/views')) {
            $this->loadViewsFrom(__DIR__.'/../Resources/views', 'differences');
        }

        // 4. Self-Healing Programmatic Database Installer
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('difference_analyses')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('difference_analysis_settings') && \Modules\Differences\Models\DifferenceAnalysisSetting::count() === 0) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', [
                    '--class' => 'Modules\\Differences\\Database\\Seeders\\DifferencesDatabaseSeeder',
                    '--force' => true
                ]);
            }

            // Programmatically register the Spatie permission and assign it to super-admin
            if (\Illuminate\Support\Facades\Schema::hasTable('permissions') && class_exists(\Spatie\Permission\Models\Permission::class)) {
                $perm = \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => 'manage_differences',
                    'guard_name' => 'web'
                ]);
                
                if (class_exists(\Spatie\Permission\Models\Role::class)) {
                    $superAdminRole = \Spatie\Permission\Models\Role::where('name', 'super-admin')->first();
                    if ($superAdminRole && !$superAdminRole->hasPermissionTo($perm)) {
                        $superAdminRole->givePermissionTo($perm);
                    }
                }
                
                // Clear permission cache
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            }
        } catch (\Exception $e) {
            // Suppress bootstrap warnings
        }
    }
}
