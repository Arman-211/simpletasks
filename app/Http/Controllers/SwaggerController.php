<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="SimpleTasks API",
 *     version="0.1",
 *     description="Документация API для SimpleTasks"
 * )
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="Employee",
 *         type="object",
 *         required={"id", "name", "email", "status"},
 *         @OA\Property(property="id", type="integer", description="ID сотрудника", example=1),
 *         @OA\Property(property="name", type="string", description="Имя сотрудника", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", description="Email сотрудника (уникальный)", example="john.doe@example.com"),
 *         @OA\Property(property="status", type="string", description="Статус сотрудника", enum={"working", "on_vacation"}, example="working"),
 *         @OA\Property(property="created_at", type="string", format="date-time", description="Дата создания", example="2025-03-07T16:00:40"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", description="Дата обновления", example="2025-03-07T16:00:40")
 *     ),
 *     @OA\Schema(
 *         schema="Task",
 *         type="object",
 *         required={"title", "description", "status"},
 *         @OA\Property(property="id", type="integer", description="The task ID", example=1),
 *         @OA\Property(property="title", type="string", description="The task title", example="Sample Task"),
 *         @OA\Property(property="description", type="string", description="The task description", example="This is a sample task."),
 *         @OA\Property(property="status", type="string", description="The task status", enum={"todo", "in_progress", "done"}, example="todo"),
 *         @OA\Property(property="created_at", type="string", format="date-time", description="Дата создания", example="2025-03-07T16:00:40"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", description="Дата обновления", example="2025-03-07T16:00:40")*     )
 *     )
 */
class SwaggerController
{
}
