<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com', 'role' => 'admin']);
    }

    public function test_admin_can_reset_user_password(): void
    {
        $targetUser = User::factory()->create(['password' => bcrypt('oldpassword')]);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.reset-password', $targetUser), [
            'password' => 'newsecretpassword',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newsecretpassword', $targetUser->fresh()->password));
    }
}
