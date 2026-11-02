<?php

namespace App\Http\Controllers;

use App\Models\Arrest;
use App\Models\Course;
use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\PersonalData;
use App\Models\Relative;
use App\Models\SecurityFile;
use App\Models\SecurityReport;
use App\Models\Suspect;
use App\Models\User;
use App\Models\Village;
use App\Models\Wife;
use App\Services\UserScopeService;
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
        $data = [
            'stats'          => $this->getDashboardStats(),
            'active_users'   => $this->activeUsers(),
            'recent_entries' => $this->recentEntries(),
            'system_stats'   => $this->systemStats(),
            'chart_data'     => $this->getChartData(),
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
            return User::where('is_active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get recent personal data entries scoped to user permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentEntries()
    {
        try {
            $query = PersonalData::with(['rank', 'internal_entity', 'governorate', 'directorate'])->latest()->limit(6);
            return UserScopeService::applyScope($query)->get();
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
                'total_users'         => User::count(),
                'total_personal_data' => UserScopeService::applyScope(PersonalData::query())->count(),
                'system_uptime'       => config('system.uptime', '99.9%'),
            ];
        } catch (\Exception $e) {
            return ['total_users' => 0, 'total_personal_data' => 0, 'system_uptime' => 'N/A'];
        }
    }

    /**
     * Get real dashboard statistics scoped by user's administrative & geographical scope.
     *
     * @return array
     */
    private function getDashboardStats()
    {
        try {
            $todayPersonal = UserScopeService::applyScope(PersonalData::whereDate('created_at', today()))->count();
            $todayReports  = UserScopeService::applyScope(SecurityReport::whereDate('created_at', today()))->count();
            $todayArrests  = UserScopeService::applyScope(Arrest::whereDate('created_at', today()))->count();

            $personalDataCount  = UserScopeService::applyScope(PersonalData::query())->count();
            $suspectsCount      = UserScopeService::applyScope(Suspect::query())->count();
            $arrestsCount       = UserScopeService::applyScope(Arrest::query())->count();
            $reportsCount       = UserScopeService::applyScope(SecurityReport::query())->count();
            $coursesCount       = UserScopeService::applyScope(Course::query())->count();
            $securityFilesCount = UserScopeService::applyScope(SecurityFile::query())->count();
            $wivesCount         = UserScopeService::applyScope(Wife::query())->count();
            $relativesCount     = UserScopeService::applyScope(Relative::query())->count();

            $criticalSuspects = UserScopeService::applyScope(Suspect::where('status', 'حرج'))->count();

            return [
                'total_today'           => $todayPersonal + $todayReports + $todayArrests,
                'personal_data_count'   => $personalDataCount,
                'suspicious_count'      => $suspectsCount,
                'critical_suspects'     => $criticalSuspects,
                'arrests_count'         => $arrestsCount,
                'reports_count'         => $reportsCount,
                'courses_count'         => $coursesCount,
                'security_files_count'  => $securityFilesCount,
                'wives_count'           => $wivesCount,
                'relatives_count'       => $relativesCount,
                'governorates_count'    => Governorate::count(),
                'directorates_count'    => Directorate::count(),
                'villages_count'        => Village::count(),
            ];
        } catch (\Exception $e) {
            return [
                'total_today'           => 0,
                'personal_data_count'   => 0,
                'suspicious_count'      => 0,
                'critical_suspects'     => 0,
                'arrests_count'         => 0,
                'reports_count'         => 0,
                'courses_count'         => 0,
                'security_files_count'  => 0,
                'wives_count'           => 0,
                'relatives_count'       => 0,
                'governorates_count'    => 0,
                'directorates_count'    => 0,
                'villages_count'        => 0
            ];
        }
    }

    /**
     * Get real chart data filtered by user scope for the last 7 days.
     */
    private function getChartData()
    {
        try {
            $daysMap = [
                'Saturday'  => 'السبت',
                'Sunday'    => 'الأحد',
                'Monday'    => 'الإثنين',
                'Tuesday'   => 'الثلاثاء',
                'Wednesday' => 'الأربعاء',
                'Thursday'  => 'الخميس',
                'Friday'    => 'الجمعة',
            ];

            $labels = [];
            $reportsDaily = [];
            $arrestsDaily = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = today()->subDays($i);
                $dayName = $daysMap[$date->format('l')] ?? $date->format('D');
                $labels[] = $dayName;

                $qRep = SecurityReport::whereDate('created_at', $date);
                UserScopeService::applyScope($qRep);
                $reportsDaily[] = $qRep->count();

                $qArr = Arrest::whereDate('created_at', $date);
                UserScopeService::applyScope($qArr);
                $arrestsDaily[] = $qArr->count();
            }

            // Suspects by status / severity
            $suspectsCriticalQ = Suspect::where('status', 'حرج');
            UserScopeService::applyScope($suspectsCriticalQ);

            $suspectsWarningQ = Suspect::where('status', 'تحذير');
            UserScopeService::applyScope($suspectsWarningQ);

            $suspectsNormalQ = Suspect::whereNotIn('status', ['حرج', 'تحذير']);
            UserScopeService::applyScope($suspectsNormalQ);

            return [
                'labels' => $labels,
                'reports_daily' => $reportsDaily,
                'arrests_daily' => $arrestsDaily,
                'suspects_breakdown' => [
                    'critical' => $suspectsCriticalQ->count(),
                    'warning'  => $suspectsWarningQ->count(),
                    'normal'   => $suspectsNormalQ->count(),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'labels' => ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
                'reports_daily' => [0, 0, 0, 0, 0, 0, 0],
                'arrests_daily' => [0, 0, 0, 0, 0, 0, 0],
                'suspects_breakdown' => ['critical' => 0, 'warning' => 0, 'normal' => 0]
            ];
        }
    }
}