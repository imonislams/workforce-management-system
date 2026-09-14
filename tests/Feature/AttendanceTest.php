<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private User $employeeUser;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $schedule = WorkSchedule::create([
            'name' => 'Standard',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'grace_period' => 15,
            'status' => 'active',
        ]);

        $this->employeeUser = User::factory()->create(['role' => 'employee']);
        $this->employee = Employee::create([
            'user_id' => $this->employeeUser->id,
            'employee_code' => 'EMP-0010',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'work_schedule_id' => $schedule->id,
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
        ]);
    }

    public function test_employee_can_check_in(): void
    {
        Carbon::setTestNow(Carbon::parse('2025-05-10 08:55:00'));

        $response = $this->actingAs($this->employeeUser)->post(route('employee.check-in'));

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance', [
            'employee_id' => $this->employee->id,
            'status' => 'present',
            'late_minutes' => 0,
        ]);
    }

    public function test_employee_check_in_marked_late_if_after_grace_period(): void
    {
        Carbon::setTestNow(Carbon::parse('2025-05-10 09:20:00'));

        $response = $this->actingAs($this->employeeUser)->post(route('employee.check-in'));

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance', [
            'employee_id' => $this->employee->id,
            'status' => 'late',
            'late_minutes' => 20,
        ]);
    }

    public function test_duplicate_check_in_is_prevented(): void
    {
        Carbon::setTestNow(Carbon::parse('2025-05-10 08:55:00'));

        $this->actingAs($this->employeeUser)->post(route('employee.check-in'));
        $response = $this->actingAs($this->employeeUser)->post(route('employee.check-in'));

        $response->assertSessionHas('error');
        $this->assertEquals(1, Attendance::where('employee_id', $this->employee->id)->count());
    }

    public function test_employee_can_check_out(): void
    {
        Carbon::setTestNow(Carbon::parse('2025-05-10 09:00:00'));
        $this->actingAs($this->employeeUser)->post(route('employee.check-in'));

        Carbon::setTestNow(Carbon::parse('2025-05-10 17:00:00'));
        $response = $this->actingAs($this->employeeUser)->post(route('employee.check-out'));

        $response->assertRedirect();
        $attendance = Attendance::where('employee_id', $this->employee->id)->first();
        $this->assertEquals(8.00, (float) $attendance->working_hours);
    }
}
