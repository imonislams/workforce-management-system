<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Setting;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Settings
        $settings = [
            'company_name' => 'Workforce WMS Enterprise',
            'company_email' => 'admin@example.com',
            'company_phone' => '+1 (555) 019-2834',
            'company_address' => '100 Tech Blvd, Suite 400, Innovation City',
            'office_start_time' => '09:00:00',
            'office_end_time' => '17:00:00',
            'grace_period' => '15',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'currency' => 'USD',
        ];

        foreach ($settings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }

        // 3. Departments
        $deptEngineering = Department::create(['name' => 'Engineering', 'description' => 'Software & Hardware R&D', 'status' => 'active']);
        $deptHR = Department::create(['name' => 'Human Resources', 'description' => 'Talent Management & Operations', 'status' => 'active']);

        // 4. Designations
        $desigDev = Designation::create(['name' => 'Software Engineer', 'description' => 'Full-stack development', 'status' => 'active']);
        $desigHR = Designation::create(['name' => 'HR Specialist', 'description' => 'People relations', 'status' => 'active']);

        // 5. Work Schedule
        $scheduleStandard = WorkSchedule::create([
            'name' => 'Standard Regular Shift',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'break_start' => '13:00:00',
            'break_end' => '14:00:00',
            'grace_period' => 15,
            'status' => 'active',
        ]);

        // 6. Leave Types
        LeaveType::create(['name' => 'Casual Leave', 'code' => 'CL', 'max_days' => 12, 'status' => 'active']);
        LeaveType::create(['name' => 'Sick Leave', 'code' => 'SL', 'max_days' => 10, 'status' => 'active']);
        LeaveType::create(['name' => 'Annual Leave', 'code' => 'AL', 'max_days' => 15, 'status' => 'active']);

        // 7. Sample Employee User
        $empUser = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        Employee::create([
            'user_id' => $empUser->id,
            'employee_code' => 'EMP-0001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+15551234567',
            'department_id' => $deptEngineering->id,
            'designation_id' => $desigDev->id,
            'work_schedule_id' => $scheduleStandard->id,
            'joining_date' => '2024-01-15',
            'employment_status' => 'active',
        ]);
    }
}
