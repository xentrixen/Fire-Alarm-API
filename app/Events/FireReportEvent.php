<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FireReportEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fireReport;
    public $eventType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($fireReport, $eventType)
    {
        $this->fireReport = $fireReport;
        $this->eventType = $eventType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('fire-reports');
    }

    public function broadcastAs()
    {
        return 'fire-report-event';
    }
}
