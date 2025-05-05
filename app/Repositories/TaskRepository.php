<?php

namespace App\Repositories;

use App\Models\Task;
use App\DTOs\TaskDTO;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TaskRepository
{
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /* Retorna todas as tarefas do  usuário */
    public function allByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function create(TaskDTO $data): Task
    {
        return Auth::user()->tasks()->create((array) $data);
    }

    public function update(Task $task, TaskDTO $data): Task
    {
        $task->update((array) $data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    /* exclusiviamente para mudar o status da task */
    public function complete(Task $task): void
    {
        $task->complete();
    }
}
