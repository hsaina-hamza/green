<?php

namespace App\Services;

use App\Models\Site;
use App\Models\WasteReport;
use App\Models\GarbageSchedule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SiteService extends BaseService
{
    /**
     * Create a new site.
     */
    public function createSite(array $data): Site
    {
        return $this->executeInTransaction(function () use ($data) {
            $site = $this->create(Site::class, $data);
            $this->logAction('create_site', ['site_id' => $site->id]);
            
            return $site;
        });
    }

    /**
     * Update an existing site.
     */
    public function updateSite(Site $site, array $data): bool
    {
        return $this->executeInTransaction(function () use ($site, $data) {
            $updated = $this->update($site, $data);
            $this->logAction('update_site', ['site_id' => $site->id]);
            
            return $updated;
        });
    }

    /**
     * Delete a site.
     */
    public function deleteSite(Site $site): bool
    {
        return $this->executeInTransaction(function () use ($site) {
            $deleted = $this->delete($site);
            $this->logAction('delete_site', ['site_id' => $site->id]);
            
            return $deleted;
        });
    }

    /**
     * Get all sites with their waste reports count.
     */
    public function getAllSitesWithStats(int $perPage = 10): LengthAwarePaginator
    {
        return Site::withCount(['wasteReports', 'garbageSchedules'])
            ->withCount([
                'wasteReports as pending_reports' => function ($query) {
                    $query->where('status', 'pending');
                },
                'wasteReports as in_progress_reports' => function ($query) {
                    $query->where('status', 'in_progress');
                },
                'wasteReports as completed_reports' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->paginate($perPage);
    }

    /**
     * Get sites within a radius (in kilometers).
     */
    public function getSitesWithinRadius(float $latitude, float $longitude, float $radius): Collection
    {
        // Rough calculation of lat/lng degrees for the given radius
        $latDelta = $radius / 111.0;
        $lngDelta = $radius / (111.0 * cos(deg2rad($latitude)));

        return Site::whereBetween('latitude', [$latitude - $latDelta, $latitude + $latDelta])
            ->whereBetween('longitude', [$longitude - $lngDelta, $longitude + $lngDelta])
            ->get();
    }

    /**
     * Get site statistics.
     */
    public function getSiteStatistics(Site $site): array
    {
        $reports = $site->wasteReports();
        $schedules = $site->garbageSchedules();

        return [
            'total_reports' => $reports->count(),
            'reports_by_status' => [
                'pending' => $reports->where('status', 'pending')->count(),
                'in_progress' => $reports->where('status', 'in_progress')->count(),
                'completed' => $reports->where('status', 'completed')->count(),
            ],
            'schedules' => [
                'total' => $schedules->count(),
                'upcoming' => $schedules->where('scheduled_time', '>', now())->count(),
                'past' => $schedules->where('scheduled_time', '<=', now())->count(),
            ],
        ];
    }

    /**
     * Get sites with active reports.
     */
    public function getSitesWithActiveReports(): Collection
    {
        return Site::whereHas('wasteReports', function ($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        })->with(['wasteReports' => function ($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        }])->get();
    }

    /**
     * Get upcoming schedules for a site.
     */
    public function getUpcomingSchedules(Site $site, int $limit = 5): Collection
    {
        return $site->garbageSchedules()
            ->where('scheduled_time', '>', now())
            ->orderBy('scheduled_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if a site has any active reports.
     */
    public function hasActiveReports(Site $site): bool
    {
        return $site->wasteReports()
            ->whereIn('status', ['pending', 'in_progress'])
            ->exists();
    }
}
