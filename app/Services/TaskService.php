<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\Jobs\NotifyTaskCreated;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;  

class TaskService
{
    public function __construct(protected TaskRepository $repository) {}

    public function list(): Collection
    {
        return $this->repository->allByUser(auth()->id()); 
    }    

    public function create(TaskDTO $dto): Task
    {
        $task = $this->repository->create($dto);
        dispatch(new NotifyTaskCreated($task));

        return $task;
    }

    public function update(Task $task, TaskDTO $dto): Task
    {
        return $this->repository->update($task, $dto);
    }

    public function delete(Task $task): void
    {
        $this->repository->delete($task);
    }

}