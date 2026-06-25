<?php

namespace Tests\Feature\Expense;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ExpenseApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $manager;
    protected $employee;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'manager', 'guard_name' => 'web']);
        Role::create(['name' => 'employee', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $permissions = [
            'expense.view',
            'expense.create',
            'expense.edit',
            'expense.delete',
            'expense.approve',
            'expense.reject',
            'expense.comment',
            'category.view',
            'category.create',
            'category.edit',
            'category.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $managerRole = Role::findByName('manager');
        $managerRole->syncPermissions(['expense.view', 'expense.approve', 'expense.reject', 'expense.comment']);

        $employeeRole = Role::findByName('employee');
        $employeeRole->syncPermissions(['expense.view', 'expense.create', 'expense.edit']);

        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions(Permission::all());

        $this->manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@test.com'
        ]);
        $this->manager->assignRole('manager');

        $this->employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@test.com'
        ]);
        $this->employee->assignRole('employee');

        $this->category = Category::create([
            'name' => 'Travel',
            'status' => 1
        ]);
    }

    public function test_manager_can_approve_pending_expense()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense for Approval',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->manager)
            ->post(route('expense.approve'), [
                'id' => $expense->id,
                'description' => 'Approved by manager',
            ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => 'approved',
        ]);
    }

    public function test_manager_can_reject_pending_expense()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense for Rejection',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->manager)
            ->post(route('expense.reject'), [
                'id' => $expense->id,
                'description' => 'Invalid expense - missing receipts',
            ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => 'rejected',
        ]);
    }

    public function test_manager_cannot_approve_already_approved_expense()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Already Approved Expense',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->manager)
            ->post(route('expense.approve'), [
                'id' => $expense->id,
                'description' => 'Try to approve again',
            ]);

        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_manager_cannot_approve_already_rejected_expense()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Already Rejected Expense',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'rejected',
        ]);

        $response = $this->actingAs($this->manager)
            ->post(route('expense.approve'), [
                'id' => $expense->id,
                'description' => 'Try to approve rejected',
            ]);

        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_employee_cannot_approve_expenses()
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

        $response = $this->actingAs($this->employee)
            ->post(route('expense.approve'), [
                'id' => $expense->id,
                'description' => 'Try to approve',
            ]);

        $response->assertStatus(403);
    }

    public function test_employee_cannot_reject_expenses()
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

        $response = $this->actingAs($this->employee)
            ->post(route('expense.reject'), [
                'id' => $expense->id,
                'description' => 'Try to reject',
            ]);

        $response->assertStatus(403);
    }

    public function test_approval_creates_notification_for_employee()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense for Notification',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $this->actingAs($this->manager)
            ->post(route('expense.approve'), [
                'id' => $expense->id,
                'description' => 'Approved',
            ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->employee->id,
            'expense_id' => $expense->id,
        ]);
    }

    public function test_rejection_creates_notification_for_employee()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense for Rejection Notification',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending',
        ]);

        $this->actingAs($this->manager)
            ->post(route('expense.reject'), [
                'id' => $expense->id,
                'description' => 'Rejected with reason',
            ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->employee->id,
            'expense_id' => $expense->id,
        ]);
    }

    public function test_reject_requires_description()
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

        $response = $this->actingAs($this->manager)
            ->post(route('expense.reject'), [
                'id' => $expense->id,
                'description' => '',
            ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_reject_description_must_be_at_least_5_characters()
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

        $response = $this->actingAs($this->manager)
            ->post(route('expense.reject'), [
                'id' => $expense->id,
                'description' => 'No',
            ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_approve_requires_valid_expense_id()
    {
        $response = $this->actingAs($this->manager)
            ->post(route('expense.approve'), [
                'id' => 99999,
                'description' => 'Test',
            ]);

        $response->assertSessionHasErrors('id');
    }

    public function test_reject_requires_valid_expense_id()
    {
        $response = $this->actingAs($this->manager)
            ->post(route('expense.reject'), [
                'id' => 99999,
                'description' => 'Test reason',
            ]);

        $response->assertSessionHasErrors('id');
    }
}
