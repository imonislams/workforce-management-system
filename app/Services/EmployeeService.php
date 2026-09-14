<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    public function generateEmployeeCode(): string
    {
        $lastEmployee = Employee::withTrashed()->latest('id')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;

        do {
            $code = 'EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $exists = Employee::withTrashed()->where('employee_code', $code)->exists();
            if ($exists) {
                $nextId++;
            }
        } while ($exists);

        return $code;
    }

    public function createEmployee(array $data, ?UploadedFile $photo = null): Employee
    {
        return DB::transaction(function () use ($data, $photo) {
            $user = User::create([
                'name' => "{$data['first_name']} {$data['last_name']}",
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password'),
                'role' => 'employee',
                'status' => $data['employment_status'] === 'active' ? 'active' : 'inactive',
            ]);

            $photoPath = null;
            if ($photo) {
                $photoPath = $photo->store('employees/photos', 'public');
            }

            return Employee::create([
                'user_id' => $user->id,
                'employee_code' => $data['employee_code'] ?? $this->generateEmployeeCode(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'designation_id' => $data['designation_id'] ?? null,
                'work_schedule_id' => $data['work_schedule_id'] ?? null,
                'joining_date' => $data['joining_date'],
                'employment_status' => $data['employment_status'] ?? 'active',
                'photo' => $photoPath,
            ]);
        });
    }

    public function updateEmployee(Employee $employee, array $data, ?UploadedFile $photo = null): Employee
    {
        return DB::transaction(function () use ($employee, $data, $photo) {
            if ($photo) {
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $data['photo'] = $photo->store('employees/photos', 'public');
            }

            $employee->update($data);

            if ($employee->user) {
                $employee->user->update([
                    'name' => "{$employee->first_name} {$employee->last_name}",
                    'email' => $employee->email,
                    'status' => $employee->employment_status === 'active' ? 'active' : 'inactive',
                ]);

                if (! empty($data['password'])) {
                    $employee->user->update([
                        'password' => Hash::make($data['password']),
                    ]);
                }
            }

            return $employee;
        });
    }

    public function toggleStatus(Employee $employee): Employee
    {
        $newStatus = $employee->employment_status === 'active' ? 'inactive' : 'active';

        DB::transaction(function () use ($employee, $newStatus) {
            $employee->update(['employment_status' => $newStatus]);
            if ($employee->user) {
                $employee->user->update(['status' => $newStatus]);
            }
        });

        return $employee;
    }
}
