<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    /**
     * @return Collection
     */
    public function getAllTasks(): Collection
    {
        return Task::with('employees')->get();
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
        $task->update($data);
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

}
