<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentDesignationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_department(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.departments.store'), [
            'name' => 'Engineering',
            'description' => 'Software development department',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseHas('departments', ['name' => 'Engineering']);
    }

    public function test_admin_can_update_department(): void
    {
        $department = Department::create(['name' => 'HR', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->put(route('admin.departments.update', $department), [
            'name' => 'Human Resources',
            'description' => 'Updated HR department',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseHas('departments', ['name' => 'Human Resources']);
    }

    public function test_admin_can_toggle_department_status(): void
    {
        $department = Department::create(['name' => 'Finance', 'status' => 'active']);

        $this->actingAs($this->admin)->patch(route('admin.departments.toggle-status', $department));
        $this->assertDatabaseHas('departments', ['id' => $department->id, 'status' => 'inactive']);
    }

    public function test_admin_can_create_designation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.designations.store'), [
            'name' => 'Senior Developer',
            'description' => 'Technical lead role',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.designations.index'));
        $this->assertDatabaseHas('designations', ['name' => 'Senior Developer']);
    }

    public function test_admin_can_update_designation(): void
    {
        $designation = Designation::create(['name' => 'Junior Dev', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->put(route('admin.designations.update', $designation), [
            'name' => 'Associate Developer',
            'description' => 'Entry level role',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.designations.index'));
        $this->assertDatabaseHas('designations', ['name' => 'Associate Developer']);
    }
}
