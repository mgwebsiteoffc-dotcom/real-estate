<?php

namespace App\Console\Commands;

use App\Models\FollowUp;
use App\Notifications\FollowUpReminderNotification;
use Illuminate\Console\Command;

class SendFollowUpReminders extends Command
{
    protected $signature = 'followups:remind';
    protected $description = 'Send follow-up reminder notifications';

    public function handle()
    {
        $dueFollowUps = FollowUp::where('status', 'pending')
            ->whereBetween('follow_up_date', [now(), now()->addHours(2)])
            ->whereDoesntHave('assignedTo.notifications', function($q) {
                $q->where('type', 'App\Notifications\FollowUpReminderNotification')
                  ->where('created_at', '>=', now()->subHours(2));
            })
            ->get();

        foreach ($dueFollowUps as $followUp) {
            $followUp->assignedTo->notify(new FollowUpReminderNotification($followUp));
            $this->info("Notification sent for follow-up #{$followUp->id}");
        }

        $this->info("Processed {$dueFollowUps->count()} follow-up reminders");
    }
}
