<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{

    /**
     * @var EmployeeService
     */
    private EmployeeService $employeeService;

    /**
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->employeeService->getAllEmployees());
    }

    /**
     * @param EmployeeRequest $request
     * @return EmployeeResource
     */
    public function store(EmployeeRequest $request): EmployeeResource
    {
        return new EmployeeResource($this->employeeService->createEmployee($request->validated()));
    }

    /**
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    /**
     * @param EmployeeRequest $request
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function update(EmployeeRequest $request, Employee $employee): EmployeeResource
    {
        return new EmployeeResource($this->employeeService->updateEmployee($employee, $request->validated()));
    }

    /**
     * @param Employee $employee
     * @return JsonResponse
     */
    public function destroy(Employee $employee): JsonResponse
    {
        return $this->employeeService->deleteEmployee($employee);
    }
}
