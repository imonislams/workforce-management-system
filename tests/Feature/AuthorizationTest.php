<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_employee_cannot_access_admin_routes(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);

        $response = $this->actingAs($employeeUser)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_employee_can_access_employee_routes(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);

        $response = $this->actingAs($employeeUser)->get('/employee/dashboard');

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_employee_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/employee/dashboard');

        $response->assertStatus(403);
    }
}
