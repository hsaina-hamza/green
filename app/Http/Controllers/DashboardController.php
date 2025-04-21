<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\Site;
use App\Models\GarbageSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $user = Auth::user();
        $stats = $this->getStats($user);
        $recentReports = $this->getRecentReports($user);
        $upcomingSchedules = $this->getUpcomingSchedules();

        return view('dashboard', compact('stats', 'recentReports', 'upcomingSchedules'));
    }

    private function getStats($user)
    {
        $stats = [
            'total_reports' => WasteReport::count(),
            'pending_reports' => WasteReport::where('status', 'pending')->count(),
            'in_progress_reports' => WasteReport::where('status', 'in_progress')->count(),
            'completed_reports' => WasteReport::where('status', 'completed')->count(),
            'total_sites' => Site::count(),
            'upcoming_schedules' => GarbageSchedule::upcoming()->count(),
        ];

        if ($user->isWorker()) {
            $stats['my_assigned_reports'] = WasteReport::where('worker_id', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();
        }

        if ($user->isUser()) {
            $stats['my_reports'] = WasteReport::where('user_id', $user->id)->count();
            $stats['my_pending_reports'] = WasteReport::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
        }

        return $stats;
    }

    private function getRecentReports($user)
    {
        $query = WasteReport::with(['user', 'site', 'assignedWorker']);

        if ($user->isWorker()) {
            $query->where('worker_id', $user->id);
        } elseif ($user->isUser()) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->take(5)->get();
    }

    private function getUpcomingSchedules()
    {
        return GarbageSchedule::with('site')
            ->upcoming()
            ->take(5)
            ->get();
    }

    public function statistics()
    {
        $monthlyStats = WasteReport::selectRaw('
                MONTH(created_at) as month,
                COUNT(*) as total_reports,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_reports
            ')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        $siteStats = Site::withCount(['wasteReports', 'garbageSchedules'])
            ->having('waste_reports_count', '>', 0)
            ->orderBy('waste_reports_count', 'desc')
            ->take(10)
            ->get();

        return view('statistics', compact('monthlyStats', 'siteStats'));
    }

    public function conservationTips()
    {
        return view('conservation-tips');
    }
}
