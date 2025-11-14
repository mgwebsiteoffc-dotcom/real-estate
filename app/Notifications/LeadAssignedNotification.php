<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LeadAssignedNotification extends Notification
{
    use Queueable;

    protected $lead;
    protected $assignedBy;

    public function __construct(Lead $lead, $assignedBy)
    {
        $this->lead = $lead;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Lead Assigned to You')
            ->line('A new lead has been assigned to you.')
            ->line('Lead: ' . $this->lead->name)
            ->line('Phone: ' . $this->lead->phone)
            ->action('View Lead', route('leads.show', $this->lead))
            ->line('Assigned by: ' . $this->assignedBy);
    }

    public function toArray($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'message' => 'New lead assigned: ' . $this->lead->name,
            'assigned_by' => $this->assignedBy,
            'url' => route('leads.show', $this->lead),
        ];
    }
}
