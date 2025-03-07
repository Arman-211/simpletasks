<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Task;
use App\Notifications\TaskStatusChanged;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAllTasks(array $filters): LengthAwarePaginator
    {
        $query = Task::with('employees');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['employee_id'])) {
            $query->whereHas('employees', function ($q) use ($filters) {
                $q->where('employee_id', $filters['employee_id']);
            });
        }

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('created_at', [$filters['date_from'], $filters['date_to']]);
        }

        if (!empty($filters['sort_by']) && in_array($filters['sort_by'], ['id', 'created_at', 'status'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction'] ?? 'asc');
        }

        return $query->paginate(10);
    }

    /**
     * @param array $data
     * @return Task|Model
     */
    public function createTask(array $data): Model|Task
    {
        return Task::query()->create($data);
    }

    /**
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        $oldStatus = $task->status;
        $task->update($data);

        if (in_array($task->status, ['in_progress', 'done']) && $oldStatus !== $task->status) {
            foreach ($task->employees as $employee) {
                $employee->notify(new TaskStatusChanged($task));
            }
        }

        return $task;
    }

    /**
     * @param Task $task
     * @return JsonResponse
     */
    public function deleteTask(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json([
            'message' => 'Task deleted successfully',
            'task_id' => $task->id
        ], Response::HTTP_OK);
    }

    /**
     * @param Task $task
     * @param Employee $employee
     * @return JsonResponse|void
     */
    public function assignTaskToEmployee(Task $task, Employee $employee)
    {
        if ($employee->status === 'on_vacation') {
            return response()->json([
                'message' => 'Cannot assign a task to an employee on vacation.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $task->employees()->attach($employee);
    }

    /**
     * @return Collection
     */
    public function getTasksGroupedByStatus(): Collection
    {
        return Task::with('employees')->get()->groupBy('status');
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function getTaskById(Task $task): Task
    {
        return $task->load('employees');
    }
}
