<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Employee;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
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
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->taskService->getAllTasks());
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", enum={"todo", "in_progress", "done"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function store(TaskRequest $request): TaskResource
    {
        return new TaskResource($this->taskService->createTask($request->validated()));
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update task by ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", enum={"todo", "in_progress", "done"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        return new TaskResource($this->taskService->updateTask($task, $request->validated()));
    }


    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get task by ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task->load('employees'));
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete task by ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted"
     *     )
     * )
     */
    public function destroy(Task $task): JsonResponse
    {
        return $this->taskService->deleteTask($task);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks/{id}/assign",
     *     summary="Assign task to an employee",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"employee_id"},
     *             @OA\Property(property="employee_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function assignToEmployee(Request $request, Task $task): JsonResponse
    {
        $employeeId = $request->input('employee_id');
        $employee = Employee::query()->findOrFail($employeeId);

        $this->taskService->assignTaskToEmployee($task, $employee);

        return response()->json(['message' => 'Task assigned successfully']);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/grouped-by-status",
     *     summary="Get tasks grouped by status",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="Tasks grouped by status",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="tasks", type="array", @OA\Items(ref="#/components/schemas/Task"))
     *         )
     *     )
     * )
     */
    public function tasksGroupedByStatus(): JsonResponse
    {
        $tasks = $this->taskService->getTasksGroupedByStatus();
        return response()->json(['tasks' => $tasks]);
    }
}
