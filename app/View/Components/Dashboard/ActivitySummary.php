<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class ActivitySummary extends Component
{
    public $pendingTasksCount;
    public $todoTasksCount;
    public $inProgressTasksCount;
    public $todayMeetingsCount;
    public $nextMeeting;
    public $clientsCount;
    public $activeClientsCount;
    public $ownerClientsCount;
    public $overdueTasksCount;
    public $person;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $pendingTasksCount = 0,
        $todoTasksCount = 0,
        $inProgressTasksCount = 0,
        $todayMeetingsCount = 0,
        $nextMeeting = null,
        $clientsCount = 0,
        $activeClientsCount = 0,
        $ownerClientsCount = 0,
        $overdueTasksCount = 0,
        $person = null
    ) {
        // Ensure all counts are proper integers
        $this->pendingTasksCount = is_numeric($pendingTasksCount) ? (int)$pendingTasksCount : 0;
        $this->todoTasksCount = is_numeric($todoTasksCount) ? (int)$todoTasksCount : 0;
        $this->inProgressTasksCount = is_numeric($inProgressTasksCount) ? (int)$inProgressTasksCount : 0;
        $this->todayMeetingsCount = is_numeric($todayMeetingsCount) ? (int)$todayMeetingsCount : 0;
        $this->nextMeeting = $nextMeeting;
        $this->clientsCount = is_numeric($clientsCount) ? (int)$clientsCount : 0;
        $this->activeClientsCount = is_numeric($activeClientsCount) ? (int)$activeClientsCount : 0;
        $this->ownerClientsCount = is_numeric($ownerClientsCount) ? (int)$ownerClientsCount : 0;
        $this->overdueTasksCount = is_numeric($overdueTasksCount) ? (int)$overdueTasksCount : 0;
        $this->person = $person;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.activity-summary');
    }
} 