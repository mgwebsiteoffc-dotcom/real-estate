<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'message' => 'You have been assigned a new task: ' . $this->task->title,
            'task_id' => $this->task->id,
            'url' => route('tasks.show', $this->task->id),
        ];
    }
}
