<?php

namespace App\Notifications;

use App\Models\FollowUp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FollowUpReminderNotification extends Notification
{
    use Queueable;

    protected $followUp;

    public function __construct(FollowUp $followUp)
    {
        $this->followUp = $followUp;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'follow_up_reminder',
            'title' => 'Follow-up Reminder',
            'message' => 'You have a follow-up scheduled with ' . $this->followUp->lead->name,
            'follow_up_id' => $this->followUp->id,
            'lead_id' => $this->followUp->lead_id,
            'follow_up_date' => $this->followUp->follow_up_date->format('M d, Y h:i A'),
            'url' => route('leads.show', $this->followUp->lead_id),
        ];
    }
}
