<?php

namespace App\Http\Controllers\Api;

use App\DTOs\TaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    /**
     * exibe a lista de tarefas do usuário
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->service->list());
    }

    /**
     * cria uma nova tarefa
     *
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $dto = TaskDTO::fromRequest($request->only('title', 'description', 'status', 'priority') + ['user_id' => auth()->id()]);
    
        $task = $this->service->create($dto);
    
        return response()->json($task, 201);
    }

    /**
     * atualiza uma tarefa existente
     *
     * @param TaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        // garantindo que o próprio usuário autenticado é o que está modificando a tarefa
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Você não tem permissão para modificar esta tarefa.'], 403);
        }

        $dto = TaskDTO::fromRequest($request->only('title', 'description', 'status', 'priority'));

        $updatedTask = $this->service->update($task, $dto);

        return response()->json($updatedTask);
    }

    /**
     * exclui uma tarefa
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        // garantindo que o próprio usuário autenticado é o que está modificando a tarefa
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Você não tem permissão para excluir esta tarefa.'], 403);
        }

        $this->service->delete($task);
        return response()->json(null, 204);
    }

    /**
     * marca uma tarefa como concluída
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function complete($id): JsonResponse
    {
        $task = Task::findOrFail($id);

        // garantindo que o próprio usuário autenticado é o que está modificando a tarefa
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Você não tem permissão para modificar esta tarefa.'], 403);
        }

        $task->status = 'completed';
        $task->save();

        return response()->json($task);
    }
}
