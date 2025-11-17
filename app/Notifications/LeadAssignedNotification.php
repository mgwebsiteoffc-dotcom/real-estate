<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification
{
    use Queueable;

    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'lead_assigned',
            'title' => 'New Lead Assigned',
            'message' => 'A new lead "' . $this->lead->name . '" has been assigned to you',
            'lead_id' => $this->lead->id,
            'url' => route('leads.show', $this->lead->id),
        ];
    }
}
