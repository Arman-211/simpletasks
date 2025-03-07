<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle.tasks')->group(function () {

    Route::get('/tasks', [TaskController::class, 'index']);

    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    Route::post('/task', [TaskController::class, 'store']);

    Route::put('/tasks/{id}', [TaskController::class, 'update']);

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::post('/tasks/{id}/assign', [TaskController::class, 'assignToEmployee']);

    Route::get('/tasks-grouped-by-status', [TaskController::class, 'tasksGroupedByStatus']);

});

Route::get('/employees', [EmployeeController::class, 'index']);

Route::get('/employees/{employee}', [EmployeeController::class, 'show']);

Route::post('/employee', [EmployeeController::class, 'store']);

Route::put('/employee/{id}', [EmployeeController::class, 'update']);

Route::delete('/employee/{id}', [EmployeeController::class, 'destroy']);

Route::post('employee/{employee}/roles', [EmployeeController::class, 'assignRole']);
