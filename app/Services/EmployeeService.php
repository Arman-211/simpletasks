<?php
namespace App\Services;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmployeeService
{
    /**
     * @return Collection
     */
    public function getAllEmployees(): Collection
    {
        return Employee::all();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createEmployee(array $data): mixed
    {
        return Employee::query()->create($data);
    }

    /**
     * @param Employee $employee
     * @param array $data
     * @return Employee
     */
    public function updateEmployee(Employee $employee, array $data): Employee
    {

        $employee->update($data);
        return $employee;
    }

    /**
     * @param Employee $employee
     * @return JsonResponse
     */
    public function deleteEmployee(Employee $employee): JsonResponse
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully'], Response::HTTP_OK);
    }
}
