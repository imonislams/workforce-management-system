<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_using_email(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'login_type' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_employee_can_authenticate_using_employee_code(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP-0001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'joining_date' => now()->toDateString(),
            'employment_status' => 'active',
        ]);

        $response = $this->post('/login', [
            'login_type' => 'employee',
            'employee_code' => 'EMP-0001',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/employee/dashboard');
    }

    public function test_inactive_users_cannot_authenticate(): void
    {
        $admin = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'login_type' => 'admin',
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
