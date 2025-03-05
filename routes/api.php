<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('tasks', TaskController::class);
Route::post('/tasks/{task}/assign', [TaskController::class, 'assignToEmployee']);
Route::get('/tasks/grouped-by-status', [TaskController::class, 'tasksGroupedByStatus']);
Route::get('/test', function () {
    return 'test route works';
});
