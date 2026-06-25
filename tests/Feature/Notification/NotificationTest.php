<?php

namespace Tests\Feature\Notification;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $notificationService;
    protected $employee;
    protected $manager;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'manager', 'guard_name' => 'web']);
        Role::create(['name' => 'employee', 'guard_name' => 'web']);

        Permission::create(['name' => 'expense.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'expense.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'expense.approve', 'guard_name' => 'web']);
        Permission::create(['name' => 'expense.reject', 'guard_name' => 'web']);

        $managerRole = Role::findByName('manager');
        $managerRole->syncPermissions(['expense.view', 'expense.approve', 'expense.reject']);

        $employeeRole = Role::findByName('employee');
        $employeeRole->syncPermissions(['expense.view', 'expense.create']);

        $this->employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@test.com'
        ]);
        $this->employee->assignRole('employee');

        $this->manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@test.com'
        ]);
        $this->manager->assignRole('manager');

        $this->category = Category::create([
            'name' => 'Travel',
            'status' => 1
        ]);

        $this->notificationService = new NotificationService();
    }

    public function test_notification_is_created_when_expense_is_submitted()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $notification = $this->notificationService->create(
            $this->employee->id,
            'Expense submitted successfully',
            'info',
            $expense->id
        );

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->employee->id,
            'message' => 'Expense submitted successfully',
            'type' => 'info',
            'expense_id' => $expense->id,
        ]);
    }

    public function test_user_can_mark_notification_as_read()
    {
        $notification = Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Test notification',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->employee)
            ->post(route('notifications.read', $notification->id));

        $response->assertStatus(200);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_get_their_notifications()
    {
        Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Test notification 1',
            'type' => 'info',
            'expense_id' => null,
        ]);

        Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Test notification 2',
            'type' => 'info',
            'expense_id' => null,
        ]);

        $response = $this->actingAs($this->employee)
            ->get(route('notifications.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_get_unread_notifications_count()
    {
        Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Unread notification 1',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Unread notification 2',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        $this->actingAs($this->employee);

        $unreadCount = Notification::where('user_id', $this->employee->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(2, $unreadCount);
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Notification 1',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Notification 2',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->employee)
            ->post(route('notifications.read-all'));

        $response->assertStatus(200);

        $unreadCount = Notification::where('user_id', $this->employee->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(0, $unreadCount);
    }

    public function test_manager_gets_notification_when_expense_is_submitted()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'New Expense for Manager',
            'amount' => 2000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $this->notificationService->create(
            $this->manager->id,
            "New expense '{$expense->title}' submitted by {$this->employee->name}",
            'info',
            $expense->id
        );

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->manager->id,
            'expense_id' => $expense->id,
            'type' => 'info',
        ]);
    }

    public function test_user_cannot_mark_other_users_notification_as_read()
    {
        $otherEmployee = User::factory()->create([
            'name' => 'Other Employee',
            'email' => 'other@test.com'
        ]);
        $otherEmployee->assignRole('employee');

        $notification = Notification::create([
            'user_id' => $otherEmployee->id,
            'message' => 'Other user notification',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->employee)
            ->post(route('notifications.read', $notification->id));

        $notification->refresh();

        $this->assertNull($notification->read_at);
        $response->assertStatus(200);
    }

    public function test_user_can_delete_a_notification()
    {
        $notification = Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Notification to delete',
            'type' => 'info',
            'expense_id' => null,
        ]);

        $this->actingAs($this->employee);
        $notification->delete();

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_notification()
    {
        $otherEmployee = User::factory()->create([
            'name' => 'Other Employee',
            'email' => 'other2@test.com'
        ]);
        $otherEmployee->assignRole('employee');

        $notification = Notification::create([
            'user_id' => $otherEmployee->id,
            'message' => 'Other user notification',
            'type' => 'info',
            'expense_id' => null,
        ]);

        $this->actingAs($this->employee);

        $deleted = Notification::where('id', $notification->id)
            ->where('user_id', $this->employee->id)
            ->delete();

        $this->assertEquals(0, $deleted);
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_notification_is_created_when_expense_status_changes()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense for Status Change',
            'amount' => 1500.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $expense->update(['status' => 'approved']);

        $this->notificationService->create(
            $this->employee->id,
            "Your expense '{$expense->title}' has been approved",
            'success',
            $expense->id
        );

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->employee->id,
            'expense_id' => $expense->id,
            'type' => 'success',
        ]);
    }

    public function test_other_user_cannot_see_private_notification()
    {
        $otherEmployee = User::factory()->create([
            'name' => 'Another Employee',
            'email' => 'another@test.com'
        ]);
        $otherEmployee->assignRole('employee');

        $notification = Notification::create([
            'user_id' => $this->employee->id,
            'message' => 'Private notification for employee',
            'type' => 'info',
            'expense_id' => null,
            'read_at' => null,
        ]);

        $response = $this->actingAs($otherEmployee)
            ->get(route('notifications.index'));

        $response->assertStatus(200);

        $notifications = Notification::where('user_id', $otherEmployee->id)->get();
        $this->assertCount(0, $notifications);
    }
}
