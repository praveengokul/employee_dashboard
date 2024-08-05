<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeDetailsPostRequest;
use App\Models\Employee;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    use HttpResponses;
    public function index()
    {
        try {
            DB::beginTransaction();

            $employee = Employee::all();

            DB::commit();
            return $this->success($employee, 'Employee lists', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }

    public function store(StoreEmployeeDetailsPostRequest $request)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::create($request->all());

            DB::commit();
            return $this->success($employee, 'Employee created successfully', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }

    public function show($id)
    {
        try {
            DB::beginTransaction();
            $employee_details = Employee::where('employee_id', $id)->first();
            // Check if employee exists
            if (!$employee_details) {
                DB::rollBack();
                return $this->error([], 'Employee not found', 404);
            }
            DB::commit();
            return $this->success($employee_details, 'Employee details successfully', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            DB::beginTransaction();
            // Find the employee by ID
            $employee = Employee::where('employee_id', $id)->first();

            // Check if employee exists
            if (!$employee) {
                DB::rollBack();
                return $this->error([], 'Employee not found', 404);
            }

            // Update the employee details
            $employee->update($request->all());

            DB::commit();
            return $this->success($employee, 'Employee details updated', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $employee = Employee::where('employee_id', $id)->first();
            // Check if employee exists
            if (!$employee) {
                DB::rollBack();
                return $this->error([], 'Employee not found', 404);
            }
            $employee->delete();
            DB::commit();

            return $this->success([], 'Employee deleted successfully', 200);
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            return $this->error([], $e->getMessage(), 422);
        }
    }
}
