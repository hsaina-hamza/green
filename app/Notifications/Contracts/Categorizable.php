<?php

namespace App\Notifications\Contracts;

interface Categorizable
{
    /**
     * Get the notification's category.
     * 
     * Categories are used to group similar notifications and allow users
     * to manage their notification preferences by category.
     * 
     * Common categories include:
     * - new_report
     * - status_update
     * - assignment
     * - comment
     * - daily_digest
     * - overdue_report
     * - unassigned_reports
     * - upcoming_schedule
     *
     * @return string
     */
    public function category(): string;
}
