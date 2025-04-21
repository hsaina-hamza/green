<?php

namespace App\Notifications;

use App\Notifications\Contracts\Categorizable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class DailyDigest extends BaseNotification implements Categorizable
{
    /**
     * The waste reports.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected Collection $reports;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $reports)
    {
        $this->reports = $reports;
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'daily_digest';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($this->getSubject())
            ->greeting("Hello {$notifiable->name}")
            ->line($this->getIntroduction());

        // Add summary statistics
        $mailMessage->line(new HtmlString($this->getStatisticsHtml()));

        // Add reports table
        $mailMessage->line(new HtmlString($this->getReportsTableHtml()));

        // Add status breakdown
        $mailMessage->line(new HtmlString($this->getStatusBreakdownHtml()));

        return $mailMessage
            ->action('View All Reports', url('/reports'))
            ->line('Thank you for staying updated with our waste management system.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'daily_digest',
            'priority' => $this->priority,
            'reports_count' => $this->reports->count(),
            'status_breakdown' => $this->getStatusBreakdown(),
            'reports' => $this->reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'type' => $report->type,
                    'status' => $report->status,
                    'site' => [
                        'id' => $report->site->id,
                        'name' => $report->site->name,
                    ],
                    'created_at' => $report->created_at->toIso8601String(),
                ];
            }),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => $this->getSubject(),
            'body' => $this->getIntroduction(),
            'data' => $this->toArray($notifiable),
        ]);
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        return sprintf(
            'Daily Waste Report Digest - %s',
            now()->format('M j, Y')
        );
    }

    /**
     * Get the introduction message.
     */
    protected function getIntroduction(): string
    {
        return sprintf(
            'Here is your daily summary of %d waste reports from %s.',
            $this->reports->count(),
            now()->subDay()->format('M j, Y')
        );
    }

    /**
     * Get HTML representation of the statistics.
     */
    protected function getStatisticsHtml(): string
    {
        $stats = $this->getStatusBreakdown();

        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">Summary Statistics</h3>
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 150px; margin: 5px; padding: 10px; background-color: #fff; border-radius: 4px; text-align: center;">
                        <div style="font-size: 24px; font-weight: bold; color: #1a56db;">%d</div>
                        <div style="color: #64748b;">Total Reports</div>
                    </div>
                    <div style="flex: 1; min-width: 150px; margin: 5px; padding: 10px; background-color: #fff; border-radius: 4px; text-align: center;">
                        <div style="font-size: 24px; font-weight: bold; color: #059669;">%d</div>
                        <div style="color: #64748b;">Completed</div>
                    </div>
                    <div style="flex: 1; min-width: 150px; margin: 5px; padding: 10px; background-color: #fff; border-radius: 4px; text-align: center;">
                        <div style="font-size: 24px; font-weight: bold; color: #dc2626;">%d</div>
                        <div style="color: #64748b;">Pending</div>
                    </div>
                </div>
            </div>',
            $this->reports->count(),
            $stats['completed'] ?? 0,
            $stats['pending'] ?? 0
        );
    }

    /**
     * Get HTML representation of the reports table.
     */
    protected function getReportsTableHtml(): string
    {
        if ($this->reports->isEmpty()) {
            return '<p style="color: #64748b;">No reports to display.</p>';
        }

        $rows = $this->reports->map(function ($report) {
            $statusColor = match($report->status) {
                'completed' => '#059669',
                'in_progress' => '#2563eb',
                default => '#dc2626',
            };

            return sprintf(
                '<tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">#%d</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        <span style="color: %s;">%s</span>
                    </td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                </tr>',
                $report->id,
                $report->type,
                $report->site->name,
                $statusColor,
                Str::title($report->status),
                $report->assignedWorker ? $report->assignedWorker->name : 'Unassigned',
                $report->created_at->format('M j, Y H:i')
            );
        })->join('');

        return sprintf(
            '<div style="margin: 20px 0; overflow-x: auto;">
                <table style="width: 100%%; border-collapse: collapse; border: 1px solid #e2e8f0;">
                    <thead>
                        <tr style="background-color: #f8fafc;">
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">ID</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Type</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Location</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Status</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Assigned To</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        %s
                    </tbody>
                </table>
            </div>',
            $rows
        );
    }

    /**
     * Get HTML representation of the status breakdown.
     */
    protected function getStatusBreakdownHtml(): string
    {
        $breakdown = $this->getStatusBreakdown();
        $total = $this->reports->count();

        if ($total === 0) {
            return '';
        }

        $bars = collect($breakdown)->map(function ($count, $status) use ($total) {
            $percentage = ($count / $total) * 100;
            $color = match($status) {
                'completed' => '#059669',
                'in_progress' => '#2563eb',
                default => '#dc2626',
            };

            return sprintf(
                '<div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="color: #1f2937;">%s</span>
                        <span style="color: #64748b;">%d (%d%%)</span>
                    </div>
                    <div style="background-color: #e2e8f0; height: 8px; border-radius: 4px;">
                        <div style="width: %d%%; background-color: %s; height: 100%%; border-radius: 4px;"></div>
                    </div>
                </div>',
                Str::title($status),
                $count,
                round($percentage),
                round($percentage),
                $color
            );
        })->join('');

        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <h3 style="margin: 0 0 15px 0; color: #1a56db;">Status Breakdown</h3>
                %s
            </div>',
            $bars
        );
    }

    /**
     * Get the status breakdown statistics.
     */
    protected function getStatusBreakdown(): array
    {
        return $this->reports->groupBy('status')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }
}
