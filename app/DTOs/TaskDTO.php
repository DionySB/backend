<?php

namespace App\DTOs;

class TaskDTO
{
    public string $title;
    public ?string $description;
    public string $priority;
    public ?string $status;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->priority = $data['priority'];
        $this->status = $data['status'] ?? 'pending';
    }

    public static function fromRequest(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
        ];
    }
}