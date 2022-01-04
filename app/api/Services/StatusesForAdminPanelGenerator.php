<?php

namespace App\Services;

use App\Interfaces\StatusesForAdminPanel;

class StatusesForAdminPanelGenerator implements StatusesForAdminPanel
{
    protected $complaintsStatuses = [
        'new',
        'viewed',
        'deleted'
    ];

    public function getComplaintsOnPostsStatuses()
    {
        return $this->complaintsStatuses;
    }

    public function getComplaintsStatusesForValidation()
    {
        $statusesForValidation = $this->complaintsStatuses;
        unset($statusesForValidation['new']);

        return $statusesForValidation;
    }
}
