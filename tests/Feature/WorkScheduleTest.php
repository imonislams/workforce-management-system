<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkScheduleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_work_schedule(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.work-schedules.store'), [
            'name' => 'Regular Shift',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'break_start' => '13:00',
            'break_end' => '14:00',
            'grace_period' => 15,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.work-schedules.index'));
        $this->assertDatabaseHas('work_schedules', ['name' => 'Regular Shift', 'grace_period' => 15]);
    }

    public function test_admin_can_update_work_schedule(): void
    {
        $schedule = WorkSchedule::create([
            'name' => 'Night Shift',
            'start_time' => '22:00',
            'end_time' => '06:00',
            'grace_period' => 10,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.work-schedules.update', $schedule), [
            'name' => 'Overnight Shift',
            'start_time' => '22:00',
            'end_time' => '06:00',
            'break_start' => null,
            'break_end' => null,
            'grace_period' => 20,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.work-schedules.index'));
        $this->assertDatabaseHas('work_schedules', ['name' => 'Overnight Shift', 'grace_period' => 20]);
    }

    public function test_admin_can_toggle_work_schedule_status(): void
    {
        $schedule = WorkSchedule::create([
            'name' => 'Weekend Shift',
            'start_time' => '10:00',
            'end_time' => '16:00',
            'grace_period' => 15,
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)->patch(route('admin.work-schedules.toggle-status', $schedule));
        $this->assertDatabaseHas('work_schedules', ['id' => $schedule->id, 'status' => 'inactive']);
    }
}
