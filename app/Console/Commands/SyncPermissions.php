<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:permissions-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions for all dynamic pages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pages = Page::all();
        $ops = ['view', 'create', 'edit', 'delete'];
        $count = 0;

        foreach ($pages as $page) {
            foreach ($ops as $op) {
                $name = "{$op}_{$page->slug}";
                $permission = Permission::where('name', $name)->where('guard_name', 'web')->first();

                if (! $permission) {
                    Permission::create([
                        'name' => $name,
                        'guard_name' => 'web',
                    ]);
                    $this->info("Created permission: {$name}");
                    $count++;
                }
            }
        }

        $this->info("Sync completed. {$count} new permissions created.");
    }
}
