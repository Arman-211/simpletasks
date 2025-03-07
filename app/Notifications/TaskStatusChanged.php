<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskStatusChanged extends Notification
{
    use Queueable;

    protected $task;

    /**
     * @param $task
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * @param $notifiable
     * @return string[]
     */
    public function toDatabase($notifiable): array
    {
        $statusNames = [
            'todo' => 'К выполнению',
            'in_progress' => 'В работе',
            'done' => 'Выполнена'
        ];

        return [
            'message' => "Задача #{$this->task->id} была переведена в статус '{$statusNames[$this->task->status]}'"
        ];
    }
}
