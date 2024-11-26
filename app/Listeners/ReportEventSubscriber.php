<?php

namespace App\Listeners;

use App\Events\ReportStatusChanged;
use App\Events\ReportSubmitted;
use App\Models\User;
use App\Notifications\ReportStatusChangedNotification;
use App\Notifications\ReportSubmittedNotification;
use Illuminate\Events\Dispatcher;

class ReportEventSubscriber
{
    /**
     * Create the event listener.
     */
    public $ReportOwner;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handleReportStatusChanged(ReportStatusChanged $event): void
    {
        $ReportOwner = User::find($event->report->user_id);
        $ReportOwner->notify(new ReportStatusChangedNotification($event));
    }

    /**
     * Handle the ReportSubmitted event by notifying the report owner.
     *
     * @param  ReportSubmitted  $event  The event instance.
     * @return void
     *
     * Sends a notification to the user who submitted the report.
     */
    public function handleReportSubmitted(ReportSubmitted $event): void
    {
        $ReportOwner = User::find($event->report->user_id);
        $ReportOwner->notify(new ReportSubmittedNotification($event));
    }

    /**
     * Subscribe to event notifications and map them to handler methods.
     *
     * @param  \Illuminate\Events\Dispatcher  $events  The events dispatcher instance.
     * @return array List of event-to-handler mappings.
     *
     * This method defines event subscription mappings for handling report-related events.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            ReportStatusChanged::class => 'handleReportStatusChanged',
            ReportSubmitted::class => 'handleReportSubmitted',
        ];
    }
}
