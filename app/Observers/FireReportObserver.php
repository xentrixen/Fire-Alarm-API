<?php

namespace App\Observers;

use App\FireReport;
use App\FirePersonnel;
use App\Events\FireReportEvent;
use App\Http\Resources\FireReport as FireReportResource;

class FireReportObserver
{
    /**
     * Handle the fire report "created" event.
     *
     * @param  \App\FireReport  $fireReport
     * @return void
     */
    public function created(FireReport $fireReport)
    {
        // Notification::send(FirePersonnel::all(), new FireReportCreated($fireReport));
        event(new FireReportEvent(new FireReportResource($fireReport)), 'created');
    }

    /**
     * Handle the fire report "updated" event.
     *
     * @param  \App\FireReport  $fireReport
     * @return void
     */
    public function updated(FireReport $fireReport)
    {
        // Notification::send(FirePersonnel::all(), new FireReportUpdated($fireReport));
    }

    /**
     * Handle the fire report "deleted" event.
     *
     * @param  \App\FireReport  $fireReport
     * @return void
     */
    public function deleted(FireReport $fireReport)
    {
        //
    }

    /**
     * Handle the fire report "restored" event.
     *
     * @param  \App\FireReport  $fireReport
     * @return void
     */
    public function restored(FireReport $fireReport)
    {
        //
    }

    /**
     * Handle the fire report "force deleted" event.
     *
     * @param  \App\FireReport  $fireReport
     * @return void
     */
    public function forceDeleted(FireReport $fireReport)
    {
        //
    }
}
