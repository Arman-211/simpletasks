<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Employee;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    private TaskService $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->middleware('throttle.tasks')->only(['store']);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->taskService->getAllTasks());
    }

    /**
     * @param TaskRequest $request
     * @return TaskResource
     */
    public function store(TaskRequest $request): TaskResource
    {
        return new TaskResource($this->taskService->createTask($request->validated()));
    }

    /**
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task->load('employees'));
    }

    /**
     * @param TaskRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        return new TaskResource($this->taskService->updateTask($task, $request->validated()));
    }

    /**
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        return $this->taskService->deleteTask($task);
    }

    /**
     * Assign a task to an employee.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function assignToEmployee(Request $request, Task $task): JsonResponse
    {
        $employeeId = $request->input('employee_id');
        $employee = Employee::findOrFail($employeeId);

        $this->taskService->assignTaskToEmployee($task, $employee);

        return response()->json(['message' => 'Task assigned successfully']);
    }

    /**
     * @return JsonResponse
     */
    public function tasksGroupedByStatus(): JsonResponse
    {
        $tasks = $this->taskService->getTasksGroupedByStatus();
        return response()->json(['tasks' => $tasks]);
    }

}
