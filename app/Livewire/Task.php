<?php

namespace App\Livewire;

use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\Task as TaskModel;

class Task extends Component
{
    public string $name = '';
    public bool $isEditing = false;
    public ?TaskModel $task = null;

    protected $listeners = [
        'updatedPriority' => 'updatePriority',
    ];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255', 'string'],
        ];
    }

    public function render(): Factory|View
    {
        return view('livewire.task', [
            'tasks' => TaskModel::orderBy('priority')->get(),
        ]);
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateTask();
        } else {
            $this->createTask();
        }

        $this->reset();

        session()->flash('status', $this->isEditing ? 'Updated successfully!' : 'Added successfully!');
    }

    protected function createTask(): void
    {
        TaskModel::create([
            'name' => $this->name,
            'priority' => $this->getLastPriority(),
        ]);
    }

    protected function updateTask(): void
    {
        if ($this->task) {
            $this->task->update(['name' => $this->name]);
        }

        $this->isEditing = false;
    }

    protected function getLastPriority(): int
    {
        return TaskModel::max('priority') + 1 ?? 1;
    }

    public function updatePriority(array $tasks): void
    {
        collect($tasks)->each(function ($task, $index) {
            TaskModel::where('id', $task['id'])->update([
                'priority' => $index + 1,
            ]);
        });
    }

    public function edit(int $taskId): void
    {
        $this->task = TaskModel::findOrFail($taskId);

        $this->name = $this->task->name;

        $this->isEditing = true;
    }

    public function delete(TaskModel $task): bool
    {
        $status = $task->delete();

        $this->reorderPriorities();

        return $status;
    }

    protected function reorderPriorities(): void
    {
        TaskModel::orderBy('priority')->get()->each(function ($task, $index) {
            $task->update(['priority' => $index + 1]);
        });
    }
}
