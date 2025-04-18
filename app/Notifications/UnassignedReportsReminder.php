<?php

namespace App\Notifications;

use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class UnassignedReportsReminder extends BaseNotification implements Categorizable, ToSms
{
    /**
     * The unassigned reports.
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
        $this->priority = 'high';
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'unassigned_reports';
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

        // Add reports table
        $mailMessage->line(new HtmlString($this->getReportsTableHtml()));

        // Add location summary if reports are from multiple sites
        if ($this->reports->unique('site_id')->count() > 1) {
            $mailMessage->line(new HtmlString($this->getLocationSummaryHtml()));
        }

        // Add action items
        $mailMessage->line('Required Actions:')
            ->line('• Review and assign these reports to available workers')
            ->line('• Consider workload distribution and worker proximity to sites')
            ->line('• Prioritize reports based on type and age');

        return $mailMessage
            ->action('Assign Reports', url('/reports?status=unassigned'))
            ->line('Please assign these reports as soon as possible to ensure timely handling.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        $count = $this->reports->count();
        $oldestHours = $this->getOldestReportHours();

        return sprintf(
            "Alert: %d unassigned waste %s (oldest: %d hours). Requires assignment. View: %s",
            $count,
            Str::plural('report', $count),
            $oldestHours,
            url('/reports?status=unassigned')
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'unassigned_reports',
            'priority' => $this->priority,
            'reports_count' => $this->reports->count(),
            'oldest_report_hours' => $this->getOldestReportHours(),
            'sites_count' => $this->reports->unique('site_id')->count(),
            'reports' => $this->reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'type' => $report->type,
                    'hours_old' => $report->created_at->diffInHours(),
                    'site' => [
                        'id' => $report->site->id,
                        'name' => $report->site->name,
                        'latitude' => $report->site->latitude,
                        'longitude' => $report->site->longitude,
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
     * Get HTML representation of the reports table.
     */
    protected function getReportsTableHtml(): string
    {
        if ($this->reports->isEmpty()) {
            return '<p style="color: #64748b;">No unassigned reports to display.</p>';
        }

        $rows = $this->reports->map(function ($report) {
            $hoursOld = $report->created_at->diffInHours();
            $urgencyColor = match(true) {
                $hoursOld >= 48 => '#dc2626', // red for >= 48 hours
                $hoursOld >= 24 => '#ea580c', // orange for >= 24 hours
                default => '#d97706', // amber for < 24 hours
            };

            return sprintf(
                '<tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">#%d</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; color: %s;">
                        <strong>%d hours</strong>
                    </td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                </tr>',
                $report->id,
                $report->type,
                $report->site->name,
                $urgencyColor,
                $hoursOld,
                $report->created_at->format('M j, Y H:i')
            );
        })->join('');

        return sprintf(
            '<div style="margin: 20px 0; overflow-x: auto;">
                <table style="width: 100%%; border-collapse: collapse; border: 1px solid #e2e8f0;">
                    <thead>
                        <tr style="background-color: #fef3c7;">
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">ID</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Type</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Location</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Age</th>
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
     * Get HTML representation of the location summary.
     */
    protected function getLocationSummaryHtml(): string
    {
        $siteSummary = $this->reports
            ->groupBy('site_id')
            ->map(function ($reports) {
                $site = $reports->first()->site;
                return [
                    'name' => $site->name,
                    'count' => $reports->count(),
                    'latitude' => $site->latitude,
                    'longitude' => $site->longitude,
                ];
            });

        $siteRows = $siteSummary->map(function ($site) {
            return sprintf(
                '<tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%s</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">%d</td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        <a href="https://www.google.com/maps?q=%f,%f" style="color: #2563eb; text-decoration: none;">
                            View on Map
                        </a>
                    </td>
                </tr>',
                $site['name'],
                $site['count'],
                $site['latitude'],
                $site['longitude']
            );
        })->join('');

        return sprintf(
            '<div style="margin: 20px 0;">
                <h3 style="color: #1f2937; margin-bottom: 10px;">Location Summary</h3>
                <table style="width: 100%%; border-collapse: collapse; border: 1px solid #e2e8f0;">
                    <thead>
                        <tr style="background-color: #f8fafc;">
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Location</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Reports</th>
                            <th style="padding: 8px; border: 1px solid #e2e8f0; text-align: left;">Map</th>
                        </tr>
                    </thead>
                    <tbody>
                        %s
                    </tbody>
                </table>
            </div>',
            $siteRows
        );
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        $count = $this->reports->count();
        return sprintf(
            '%d Unassigned Waste %s Need Attention',
            $count,
            Str::plural('Report', $count)
        );
    }

    /**
     * Get the introduction message.
     */
    protected function getIntroduction(): string
    {
        $count = $this->reports->count();
        $oldestHours = $this->getOldestReportHours();
        $sites = $this->reports->unique('site_id')->count();

        return sprintf(
            'There %s %d unassigned waste %s across %d %s that require assignment. The oldest report was submitted %d hours ago.',
            $count === 1 ? 'is' : 'are',
            $count,
            Str::plural('report', $count),
            $sites,
            Str::plural('location', $sites),
            $oldestHours
        );
    }

    /**
     * Get the number of hours the oldest report has been unassigned.
     */
    protected function getOldestReportHours(): int
    {
        if ($this->reports->isEmpty()) {
            return 0;
        }

        return $this->reports->max(function ($report) {
            return $report->created_at->diffInHours();
        });
    }
}
