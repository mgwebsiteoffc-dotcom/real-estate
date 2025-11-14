<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskDueNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Due Soon: ' . $this->task->title)
            ->line('You have a task due soon.')
            ->line('Task: ' . $this->task->title)
            ->line('Due: ' . $this->task->due_date->format('M d, Y h:i A'))
            ->action('View Task', route('tasks.show', $this->task));
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'message' => 'Task due: ' . $this->task->title,
            'due_date' => $this->task->due_date->format('M d, Y h:i A'),
            'url' => route('tasks.show', $this->task),
        ];
    }
}
