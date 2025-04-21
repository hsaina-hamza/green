<?php

namespace App\Services;

use App\Models\GarbageSchedule;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GarbageScheduleService extends BaseService
{
    /**
     * Create a new garbage collection schedule.
     */
    public function createSchedule(array $data): GarbageSchedule
    {
        return $this->executeInTransaction(function () use ($data) {
            $schedule = $this->create(GarbageSchedule::class, $data);
            $this->logAction('create_schedule', ['schedule_id' => $schedule->id]);
            
            return $schedule;
        });
    }

    /**
     * Update an existing schedule.
     */
    public function updateSchedule(GarbageSchedule $schedule, array $data): bool
    {
        return $this->executeInTransaction(function () use ($schedule, $data) {
            $updated = $this->update($schedule, $data);
            $this->logAction('update_schedule', ['schedule_id' => $schedule->id]);
            
            return $updated;
        });
    }

    /**
     * Delete a schedule.
     */
    public function deleteSchedule(GarbageSchedule $schedule): bool
    {
        return $this->executeInTransaction(function () use ($schedule) {
            $deleted = $this->delete($schedule);
            $this->logAction('delete_schedule', ['schedule_id' => $schedule->id]);
            
            return $deleted;
        });
    }

    /**
     * Get upcoming schedules.
     */
    public function getUpcomingSchedules(int $perPage = 10): LengthAwarePaginator
    {
        return GarbageSchedule::with('site')
            ->where('scheduled_time', '>', now())
            ->orderBy('scheduled_time')
            ->paginate($perPage);
    }

    /**
     * Get past schedules.
     */
    public function getPastSchedules(int $perPage = 10): LengthAwarePaginator
    {
        return GarbageSchedule::with('site')
            ->where('scheduled_time', '<=', now())
            ->orderByDesc('scheduled_time')
            ->paginate($perPage);
    }

    /**
     * Get today's schedules.
     */
    public function getTodaySchedules(): Collection
    {
        return GarbageSchedule::with('site')
            ->whereDate('scheduled_time', Carbon::today())
            ->orderBy('scheduled_time')
            ->get();
    }

    /**
     * Get schedules for a specific date range.
     */
    public function getSchedulesForDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return GarbageSchedule::with('site')
            ->whereBetween('scheduled_time', [$startDate, $endDate])
            ->orderBy('scheduled_time')
            ->get();
    }

    /**
     * Create weekly schedules for a site.
     */
    public function createWeeklySchedules(Site $site, array $weeklyData): Collection
    {
        return $this->executeInTransaction(function () use ($site, $weeklyData) {
            $schedules = collect();

            foreach ($weeklyData as $data) {
                $weekday = $data['weekday']; // 1 (Monday) to 7 (Sunday)
                $hour = $data['hour'];
                $minute = $data['minute'] ?? 0;
                $truckNumber = $data['truck_number'];
                $weeks = $data['weeks'] ?? 4; // Number of weeks to schedule ahead

                for ($week = 1; $week <= $weeks; $week++) {
                    $scheduledTime = Carbon::now()
                        ->addWeeks($week)
                        ->startOfWeek()
                        ->addDays($weekday - 1)
                        ->setHour($hour)
                        ->setMinute($minute)
                        ->setSecond(0);

                    $schedule = $this->createSchedule([
                        'site_id' => $site->id,
                        'truck_number' => $truckNumber,
                        'scheduled_time' => $scheduledTime,
                    ]);

                    $schedules->push($schedule);
                }
            }

            $this->logAction('create_weekly_schedules', [
                'site_id' => $site->id,
                'count' => $schedules->count(),
            ]);

            return $schedules;
        });
    }

    /**
     * Get schedule statistics.
     */
    public function getStatistics(): array
    {
        $now = Carbon::now();
        
        return [
            'total' => GarbageSchedule::count(),
            'upcoming' => GarbageSchedule::where('scheduled_time', '>', $now)->count(),
            'past' => GarbageSchedule::where('scheduled_time', '<=', $now)->count(),
            'today' => GarbageSchedule::whereDate('scheduled_time', Carbon::today())->count(),
            'this_week' => GarbageSchedule::whereBetween('scheduled_time', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->count(),
        ];
    }

    /**
     * Check for schedule conflicts.
     */
    public function hasScheduleConflict(Site $site, Carbon $scheduledTime, int $excludeScheduleId = null): bool
    {
        $query = GarbageSchedule::where('site_id', $site->id)
            ->where('scheduled_time', $scheduledTime);

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        return $query->exists();
    }
}
