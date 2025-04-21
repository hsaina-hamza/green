<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Notifications\Contracts\Categorizable;
use App\Notifications\Contracts\ToSms;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class NewComment extends BaseNotification implements Categorizable, ToSms
{
    /**
     * The comment.
     *
     * @var \App\Models\Comment
     */
    protected Comment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->priority = $this->determinePriority();
    }

    /**
     * Get the notification's category.
     */
    public function category(): string
    {
        return 'comment';
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

        // Add comment details
        $mailMessage->line(new HtmlString($this->getCommentDetailsHtml()));

        // Add report context
        $mailMessage->line(new HtmlString($this->getReportContextHtml()));

        // Add role-specific information
        if ($notifiable->role === 'admin') {
            $mailMessage->line('As an administrator, you can:')
                ->line('• Review and respond to the comment')
                ->line('• Take necessary actions based on the comment')
                ->line('• Monitor the discussion for any issues');
        } elseif ($notifiable->id === $this->comment->waste_report->assigned_worker_id) {
            $mailMessage->line('As the assigned worker, please:')
                ->line('• Review the comment for any instructions or questions')
                ->line('• Respond if necessary')
                ->line('• Update the report status if needed');
        }

        // Add thread context if this is a reply
        if ($this->comment->parent_id) {
            $mailMessage->line(new HtmlString($this->getCommentThreadHtml()));
        }

        return $mailMessage
            ->action('View Comment', url("/reports/{$this->comment->waste_report_id}#comment-{$this->comment->id}"))
            ->line('You can reply to this comment directly from the report page.');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        return sprintf(
            "New comment on Report #%d by %s: %s. View at: %s",
            $this->comment->waste_report_id,
            $this->comment->user->name,
            Str::limit($this->comment->text, 50),
            url("/reports/{$this->comment->waste_report_id}#comment-{$this->comment->id}")
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_comment',
            'priority' => $this->priority,
            'comment' => [
                'id' => $this->comment->id,
                'text' => $this->comment->text,
                'parent_id' => $this->comment->parent_id,
                'user' => [
                    'id' => $this->comment->user->id,
                    'name' => $this->comment->user->name,
                ],
                'created_at' => $this->comment->created_at->toIso8601String(),
            ],
            'report' => [
                'id' => $this->comment->waste_report_id,
                'type' => $this->comment->waste_report->type,
                'status' => $this->comment->waste_report->status,
            ],
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
     * Get HTML representation of the comment details.
     */
    protected function getCommentDetailsHtml(): string
    {
        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <div style="margin-bottom: 10px;">
                    <span style="color: #1f2937; font-weight: bold;">%s</span>
                    <span style="color: #64748b;"> • %s</span>
                </div>
                <div style="padding: 10px; background-color: white; border-radius: 4px; border: 1px solid #e2e8f0;">
                    %s
                </div>
            </div>',
            $this->comment->user->name,
            $this->comment->created_at->diffForHumans(),
            nl2br(e($this->comment->text))
        );
    }

    /**
     * Get HTML representation of the report context.
     */
    protected function getReportContextHtml(): string
    {
        $report = $this->comment->waste_report;
        $statusColors = [
            'pending' => '#dc2626',
            'in_progress' => '#2563eb',
            'completed' => '#059669',
        ];

        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">Report Context</h3>
                <table style="width: 100%%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Report ID:</td>
                        <td style="padding: 8px 0;">#%d</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Type:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Status:</td>
                        <td style="padding: 8px 0;">
                            <span style="color: %s;">%s</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;">Location:</td>
                        <td style="padding: 8px 0;">%s</td>
                    </tr>
                </table>
            </div>',
            $report->id,
            Str::title($report->type),
            $statusColors[$report->status] ?? '#64748b',
            Str::title($report->status),
            $report->site->name
        );
    }

    /**
     * Get HTML representation of the comment thread.
     */
    protected function getCommentThreadHtml(): string
    {
        $parentComment = $this->comment->parent;

        return sprintf(
            '<div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                <h3 style="margin: 0 0 10px 0; color: #1a56db;">In Reply To</h3>
                <div style="padding: 10px; background-color: white; border-radius: 4px; border: 1px solid #e2e8f0;">
                    <div style="margin-bottom: 5px;">
                        <span style="color: #1f2937; font-weight: bold;">%s</span>
                        <span style="color: #64748b;"> • %s</span>
                    </div>
                    <div style="color: #64748b;">%s</div>
                </div>
            </div>',
            $parentComment->user->name,
            $parentComment->created_at->diffForHumans(),
            Str::limit($parentComment->text, 150)
        );
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        return sprintf(
            'New Comment on Waste Report #%d',
            $this->comment->waste_report_id
        );
    }

    /**
     * Get the introduction message.
     */
    protected function getIntroduction(): string
    {
        if ($this->comment->parent_id) {
            return sprintf(
                '%s replied to a comment on waste report #%d.',
                $this->comment->user->name,
                $this->comment->waste_report_id
            );
        }

        return sprintf(
            '%s added a new comment to waste report #%d.',
            $this->comment->user->name,
            $this->comment->waste_report_id
        );
    }

    /**
     * Determine the notification priority.
     */
    protected function determinePriority(): string
    {
        // High priority if comment contains urgent keywords
        if (Str::contains(strtolower($this->comment->text), ['urgent', 'emergency', 'asap', 'immediately'])) {
            return 'high';
        }

        // High priority if comment is from an admin
        if ($this->comment->user->role === 'admin') {
            return 'high';
        }

        return 'normal';
    }

    /**
     * Get the notification's channels.
     */
    public function via($notifiable): array
    {
        // Always include database and broadcast
        $channels = ['database', 'broadcast'];

        // Add mail for all comments
        if ($this->shouldSendEmail($notifiable)) {
            $channels[] = 'mail';
        }

        // Add SMS for high priority comments or if the user is the assigned worker
        if ($this->shouldSendSms($notifiable) && 
            ($this->priority === 'high' || 
             $notifiable->id === $this->comment->waste_report->assigned_worker_id)) {
            $channels[] = 'sms';
        }

        return $channels;
    }
}
