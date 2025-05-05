<?php

namespace Tests\Feature;

use App\Jobs\NotifyTaskCreated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotAccessTasks(): void
    {
        $this->getJson('/api/tasks')->assertUnauthorized();
        $this->postJson('/api/tasks', [])->assertUnauthorized();
    }

    public function testUserCanListTasks(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertOk();
        $response->assertJsonCount(3);
    }

    public function testUserCanCreateTask(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Tarefa Teste',
            'description' => 'Descrição',
            'priority' => 'high',
            'status' => 'pending',
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('tasks', ['title' => 'Tarefa Teste']);
    }

    public function testUserCanUpdateTask(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $payload = [
            'title' => 'Atualizado',
            'priority' => 'medium',
            'status' => 'completed',
        ];

        $response = $this->actingAs($user)->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Atualizado']);
    }

    public function testUserCanDeleteTask(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testValidationFailsForMissingFields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'priority']);
    }
}
