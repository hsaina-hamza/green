<?php

namespace App\Services;

use App\Models\WasteReport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WasteReportService extends BaseService
{
    /**
     * Create a new waste report.
     */
    public function createReport(array $data, ?UploadedFile $image = null): WasteReport
    {
        return $this->executeInTransaction(function () use ($data, $image) {
            if ($image) {
                $data['image_path'] = $this->handleFileUpload($image, 'waste-reports');
            }

            $report = $this->create(WasteReport::class, $data);
            $this->logAction('create_report', ['report_id' => $report->id]);
            
            return $report;
        });
    }

    /**
     * Update an existing waste report.
     */
    public function updateReport(WasteReport $report, array $data, ?UploadedFile $image = null): bool
    {
        return $this->executeInTransaction(function () use ($report, $data, $image) {
            if ($image) {
                if ($report->image_path) {
                    $this->deleteFile($report->image_path);
                }
                $data['image_path'] = $this->handleFileUpload($image, 'waste-reports');
            }

            $updated = $this->update($report, $data);
            $this->logAction('update_report', ['report_id' => $report->id]);
            
            return $updated;
        });
    }

    /**
     * Delete a waste report.
     */
    public function deleteReport(WasteReport $report): bool
    {
        return $this->executeInTransaction(function () use ($report) {
            if ($report->image_path) {
                $this->deleteFile($report->image_path);
            }

            $deleted = $this->delete($report);
            $this->logAction('delete_report', ['report_id' => $report->id]);
            
            return $deleted;
        });
    }

    /**
     * Assign a worker to a report.
     */
    public function assignWorker(WasteReport $report, User $worker): bool
    {
        if ($worker->role !== 'worker') {
            throw new \InvalidArgumentException('User must be a worker');
        }

        return $this->executeInTransaction(function () use ($report, $worker) {
            $updated = $this->update($report, [
                'assigned_worker_id' => $worker->id,
                'status' => 'in_progress'
            ]);

            $this->logAction('assign_worker', [
                'report_id' => $report->id,
                'worker_id' => $worker->id
            ]);
            
            return $updated;
        });
    }

    /**
     * Update report status.
     */
    public function updateStatus(WasteReport $report, string $status): bool
    {
        if (!in_array($status, ['pending', 'in_progress', 'completed'])) {
            throw new \InvalidArgumentException('Invalid status');
        }

        return $this->executeInTransaction(function () use ($report, $status) {
            $updated = $this->update($report, ['status' => $status]);
            
            $this->logAction('update_status', [
                'report_id' => $report->id,
                'status' => $status
            ]);
            
            return $updated;
        });
    }

    /**
     * Get reports by status.
     */
    public function getReportsByStatus(string $status, int $perPage = 10): LengthAwarePaginator
    {
        return $this->getPaginated(
            WasteReport::class,
            ['status' => $status],
            ['user', 'site', 'assignedWorker'],
            $perPage
        );
    }

    /**
     * Get reports assigned to a worker.
     */
    public function getWorkerReports(User $worker, int $perPage = 10): LengthAwarePaginator
    {
        return $this->getPaginated(
            WasteReport::class,
            ['assigned_worker_id' => $worker->id],
            ['user', 'site'],
            $perPage
        );
    }

    /**
     * Get reports created by a user.
     */
    public function getUserReports(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->getPaginated(
            WasteReport::class,
            ['user_id' => $user->id],
            ['site', 'assignedWorker'],
            $perPage
        );
    }

    /**
     * Get reports statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => WasteReport::count(),
            'pending' => WasteReport::where('status', 'pending')->count(),
            'in_progress' => WasteReport::where('status', 'in_progress')->count(),
            'completed' => WasteReport::where('status', 'completed')->count(),
        ];
    }
}
