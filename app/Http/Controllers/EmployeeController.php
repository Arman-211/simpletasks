<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Get all employees",
     *     tags={"Employees"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all employees",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->employeeService->getAllEmployees());
    }

    /**
     * @OA\Post(
     *     path="/api/employees",
     *     summary="Create a new employee",
     *     tags={"Employees"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "position"},
     *             @OA\Property(property="name", type="string", description="Employee name"),
     *             @OA\Property(property="email", type="string", format="email", description="Employee email"),
     *             @OA\Property(property="position", type="string", description="Employee position")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Employee created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
     *     )
     * )
     */
    public function store(EmployeeRequest $request): EmployeeResource
    {
        return new EmployeeResource($this->employeeService->createEmployee($request->validated()));
    }

    /**
     * @OA\Get(
     *     path="/api/employees/{id}",
     *     summary="Get an employee by ID",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee details",
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     )
     * )
     */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($this->employeeService->getEmployeeById($employee));
    }

    /**
     * @OA\Put(
     *     path="/api/employees/{id}",
     *     summary="Update an employee by ID",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "position"},
     *             @OA\Property(property="name", type="string", description="Employee name"),
     *             @OA\Property(property="email", type="string", format="email", description="Employee email"),
     *             @OA\Property(property="position", type="string", description="Employee position")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     )
     * )
     */
    public function update(EmployeeRequest $request, Employee $id): EmployeeResource
    {
        return new EmployeeResource($this->employeeService->updateEmployee($id, $request->validated()));
    }

    /**
     * @OA\Delete(
     *     path="/api/employees/{id}",
     *     summary="Delete an employee by ID",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Employee deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     )
     * )
     */
    public function destroy(Employee $id): JsonResponse
    {
        return $this->employeeService->deleteEmployee($id);
    }

    /**
     * @param Request $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function assignRole(Request $request, Employee $employee): JsonResponse
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $employee->roles()->sync($request->roles);

        return response()->json(['message' => 'Роли обновлены']);
    }

}
