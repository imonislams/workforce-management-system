<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_employee(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), [
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['email' => 'alice@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'alice@example.com', 'role' => 'employee']);
    }

    public function test_admin_can_update_employee(): void
    {
        $user = User::factory()->create(['email' => 'bob@example.com', 'role' => 'employee']);
        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP-0005',
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'email' => 'bob@example.com',
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.employees.update', $employee), [
            'first_name' => 'Robert',
            'last_name' => 'Jones',
            'email' => 'robert@example.com',
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['email' => 'robert@example.com', 'first_name' => 'Robert']);
        $this->assertDatabaseHas('users', ['email' => 'robert@example.com']);
    }

    public function test_admin_can_toggle_employee_status(): void
    {
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP-0006',
            'first_name' => 'Charlie',
            'last_name' => 'Brown',
            'email' => 'charlie@example.com',
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
        ]);

        $this->actingAs($this->admin)->patch(route('admin.employees.toggle-status', $employee));
        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'employment_status' => 'inactive']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'inactive']);
    }
}
