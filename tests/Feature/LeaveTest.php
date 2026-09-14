<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $employeeUser;
    private Employee $employee;
    private LeaveType $leaveType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->employeeUser = User::factory()->create(['role' => 'employee']);
        $this->employee = Employee::create([
            'user_id' => $this->employeeUser->id,
            'employee_code' => 'EMP-0020',
            'first_name' => 'Mark',
            'last_name' => 'Taylor',
            'email' => 'mark@example.com',
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
        ]);
        $this->leaveType = LeaveType::create([
            'name' => 'Casual Leave',
            'code' => 'CL',
            'max_days' => 12,
            'status' => 'active',
        ]);
    }

    public function test_employee_can_apply_for_leave(): void
    {
        $response = $this->actingAs($this->employeeUser)->post(route('employee.leaves.store'), [
            'leave_type_id' => $this->leaveType->id,
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-03',
            'reason' => 'Family event',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leaves', [
            'employee_id' => $this->employee->id,
            'total_days' => 3,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_leave(): void
    {
        $leave = Leave::create([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $this->leaveType->id,
            'start_date' => '2025-06-10',
            'end_date' => '2025-06-12',
            'total_days' => 3,
            'reason' => 'Vacation',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.leaves.approve', $leave), [
            'admin_note' => 'Approved, have fun!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => 'approved',
            'admin_note' => 'Approved, have fun!',
        ]);
    }
}
