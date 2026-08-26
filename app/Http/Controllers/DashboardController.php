<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Prepare data for dashboard
        $data = [
            'stats' => $this->getDashboardStats(),
            'active_users' => $this->activeUsers(),
            'recent_entries' => $this->recentEntries(),
            'system_stats' => $this->systemStats(),
            'user_triggers' => $this->userTriggers(),
        ];

        return view('dashboard', $data);
    }

    /**
     * Get active users count.
     *
     * @return int
     */
    public function activeUsers()
    {
        try {
            // Assuming you have a User model
            return \App\Models\User::where('last_login_at', '>', now()->subHours(24))->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get recent entries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentEntries()
    {
        try {
            // Assuming you have an Entry model
            return \App\Models\Entry::orderBy('created_at', 'desc')->limit(10)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get system statistics.
     *
     * @return array
     */
    public function systemStats()
    {
        try {
            return [
                'total_users' => \App\Models\User::count(),
                'total_entries' => \App\Models\Entry::count(),
                'system_uptime' => config('system.uptime', '99.9%'),
            ];
        } catch (\Exception $e) {
            return ['total_users' => 0, 'total_entries' => 0, 'system_uptime' => 'N/A'];
        }
    }

    /**
     * Get user triggers/actions.
     *
     * @return array
     */
    public function userTriggers()
    {
        try {
            return \App\Models\UserTrigger::orderBy('created_at', 'desc')->limit(5)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    private function getDashboardStats()
    {
        try {
            return [
                'total_today' => \App\Models\Entry::whereDate('created_at', today())->count(),
                'personal_data_count' => \App\Models\PersonalData::count(),
                'governorates_count' => \App\Models\Governorate::count(),
                'directorates_count' => \App\Models\Directorate::count(),
                'villages_count' => \App\Models\Village::count(),
            ];
        } catch (\Exception $e) {
            return ['total_today' => 0, 'personal_data_count' => 0, 'governorates_count' => 0, 'directorates_count' => 0, 'villages_count' => 0];
        }
    }
}