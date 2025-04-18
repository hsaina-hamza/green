<?php

namespace App\Services;

use App\Models\WasteReport;
use App\Models\Site;
use App\Models\GarbageSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseService
{
    /**
     * Get overall system statistics.
     */
    public function getOverallStatistics(): array
    {
        return [
            'reports' => $this->getReportStatistics(),
            'sites' => $this->getSiteStatistics(),
            'schedules' => $this->getScheduleStatistics(),
            'users' => $this->getUserStatistics(),
        ];
    }

    /**
     * Get report statistics.
     */
    private function getReportStatistics(): array
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        return [
            'total' => WasteReport::count(),
            'by_status' => [
                'pending' => WasteReport::where('status', 'pending')->count(),
                'in_progress' => WasteReport::where('status', 'in_progress')->count(),
                'completed' => WasteReport::where('status', 'completed')->count(),
            ],
            'recent' => [
                'today' => WasteReport::whereDate('created_at', $now)->count(),
                'this_week' => WasteReport::where('created_at', '>=', $startOfWeek)->count(),
                'this_month' => WasteReport::where('created_at', '>=', $startOfMonth)->count(),
            ],
            'trends' => $this->getReportTrends(),
        ];
    }

    /**
     * Get site statistics.
     */
    private function getSiteStatistics(): array
    {
        return [
            'total' => Site::count(),
            'with_active_reports' => Site::whereHas('wasteReports', function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            })->count(),
            'most_active' => Site::withCount('wasteReports')
                ->orderByDesc('waste_reports_count')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Get schedule statistics.
     */
    private function getScheduleStatistics(): array
    {
        $now = Carbon::now();

        return [
            'total' => GarbageSchedule::count(),
            'upcoming' => [
                'today' => GarbageSchedule::whereDate('scheduled_time', $now)->count(),
                'this_week' => GarbageSchedule::whereBetween('scheduled_time', [
                    $now,
                    $now->copy()->endOfWeek(),
                ])->count(),
            ],
            'completed' => GarbageSchedule::where('scheduled_time', '<', $now)->count(),
        ];
    }

    /**
     * Get user statistics.
     */
    private function getUserStatistics(): array
    {
        return [
            'total' => User::count(),
            'by_role' => [
                'admin' => User::where('role', 'admin')->count(),
                'worker' => User::where('role', 'worker')->count(),
                'user' => User::where('role', 'user')->count(),
            ],
            'active_workers' => User::where('role', 'worker')
                ->whereHas('assignedReports', function ($query) {
                    $query->whereIn('status', ['pending', 'in_progress']);
                })
                ->count(),
        ];
    }

    /**
     * Get report trends over time.
     */
    private function getReportTrends(): array
    {
        $monthlyReports = WasteReport::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return $monthlyReports->map(function ($item) {
            return [
                'period' => Carbon::createFromDate($item->year, $item->month, 1)->format('Y-m'),
                'total' => $item->total,
                'completed' => $item->completed,
                'completion_rate' => $item->total > 0 ? round(($item->completed / $item->total) * 100, 2) : 0,
            ];
        })->toArray();
    }

    /**
     * Get worker performance statistics.
     */
    public function getWorkerPerformance(User $worker): array
    {
        $reports = $worker->assignedReports();

        return [
            'total_assigned' => $reports->count(),
            'completed' => $reports->where('status', 'completed')->count(),
            'in_progress' => $reports->where('status', 'in_progress')->count(),
            'average_completion_time' => $this->calculateAverageCompletionTime($worker),
            'recent_activity' => $reports->with('site')
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Calculate average completion time for a worker.
     */
    private function calculateAverageCompletionTime(User $worker): ?float
    {
        $completedReports = $worker->assignedReports()
            ->where('status', 'completed')
            ->get();

        if ($completedReports->isEmpty()) {
            return null;
        }

        $totalTime = $completedReports->sum(function ($report) {
            $assigned = Carbon::parse($report->updated_at)->diffInHours(Carbon::parse($report->created_at));
            return $assigned;
        });

        return round($totalTime / $completedReports->count(), 2);
    }

    /**
     * Get recent activity feed.
     */
    public function getRecentActivity(int $limit = 10): Collection
    {
        return WasteReport::with(['user', 'site', 'assignedWorker'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($report) {
                return [
                    'type' => 'report',
                    'action' => $this->determineReportAction($report),
                    'data' => $report,
                    'timestamp' => $report->updated_at,
                ];
            });
    }

    /**
     * Determine the action type for a report.
     */
    private function determineReportAction(WasteReport $report): string
    {
        if ($report->created_at->eq($report->updated_at)) {
            return 'created';
        }

        if ($report->status === 'completed') {
            return 'completed';
        }

        if ($report->assigned_worker_id) {
            return 'assigned';
        }

        return 'updated';
    }
}
