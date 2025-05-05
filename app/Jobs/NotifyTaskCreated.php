<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Log;

class NotifyTaskCreated implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle(): void
    {
        Log::info('Nova notificação: Você tem uma nova tarefa: ' . $this->task->title);
    }

    // Faz tempo que não mexo com Jobs & queues, fiz o método para disparar diretamente
    public static function dispatchTask(Task $task)
    {
        dispatch(new self($task));
    }
}
