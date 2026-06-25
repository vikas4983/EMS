<?php

namespace Tests\Feature\Expense;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ExpenseCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $employee;
    protected $manager;
    protected $category;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $roles = ['admin', 'manager', 'employee'];
        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

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

        $this->employee = User::factory()->create([
            'name' => 'Test Employee',
            'email' => 'employee@test.com'
        ]);
        $this->employee->assignRole('employee');

        $employeeRole = Role::findByName('employee');
        $employeeRole->syncPermissions(['expense.view', 'expense.create', 'expense.edit']);

        $this->manager = User::factory()->create([
            'name' => 'Test Manager',
            'email' => 'manager@test.com'
        ]);
        $this->manager->assignRole('manager');

        $managerRole = Role::findByName('manager');
        $managerRole->syncPermissions(['expense.view', 'expense.approve', 'expense.reject', 'expense.comment']);

        $this->admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com'
        ]);
        $this->admin->assignRole('admin');

        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions(Permission::all());

        $this->category = Category::create([
            'name' => 'Travel',
            'status' => 1
        ]);
    }

    public function test_employee_can_create_expense_with_valid_data()
    {
        $expenseData = [
            'title' => 'Business Trip to Mumbai',
            'amount' => 5000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'description' => 'Travel expenses for client meeting'
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertRedirect(route('expenses.index'));
        $response->assertSessionHas('success', 'Expense created successfully.');

        $this->assertDatabaseHas('expenses', [
            'title' => 'Business Trip to Mumbai',
            'amount' => 5000.00,
            'status' => 'pending',
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id
        ]);
    }

    public function test_expense_creation_stores_all_required_fields()
    {
        $expenseData = [
            'title' => 'Office Supplies',
            'amount' => 2500.75,
            'expense_date' => now()->subDays(2)->format('Y-m-d'),
            'category_id' => $this->category->id,
            'description' => 'Purchased stationery items'
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $this->assertDatabaseHas('expenses', [
            'title' => 'Office Supplies',
            'amount' => 2500.75,
            'description' => 'Purchased stationery items',
            'status' => 'pending',
            'user_id' => $this->employee->id,
        ]);
    }

    public function test_employee_can_upload_single_receipt_with_expense()
    {
        $receipt = UploadedFile::fake()->create('receipt.pdf', 1024, 'application/pdf');

        $expenseData = [
            'title' => 'Lunch with Client',
            'amount' => 1200.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'description' => 'Business lunch',
            'receipts' => [$receipt]
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertRedirect();

        $expense = Expense::where('title', 'Lunch with Client')->first();
        $this->assertNotNull($expense);
        $this->assertCount(1, $expense->receipts);

        $this->assertDatabaseHas('expense_receipts', [
            'expense_id' => $expense->id,
            'file_name' => 'receipt.pdf'
        ]);
    }

    public function test_employee_can_upload_multiple_receipts_with_expense()
    {
        $receipt1 = UploadedFile::fake()->create('receipt1.pdf', 1024, 'application/pdf');
        $receipt2 = UploadedFile::fake()->create('receipt2.pdf', 2048, 'application/pdf');
        $receipt3 = UploadedFile::fake()->create('receipt3.pdf', 512, 'application/pdf');

        $expenseData = [
            'title' => 'Conference Expenses',
            'amount' => 15000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'description' => 'Tech conference registration and travel',
            'receipts' => [$receipt1, $receipt2, $receipt3]
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertRedirect();

        $expense = Expense::where('title', 'Conference Expenses')->first();
        $this->assertNotNull($expense);
        $this->assertCount(3, $expense->receipts);
    }

    public function test_expense_requires_title_field()
    {
        $expenseData = [
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors('title');
    }

    public function test_expense_requires_amount_field()
    {
        $expenseData = [
            'title' => 'Test Expense',
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors('amount');
    }

    public function test_expense_amount_must_be_positive()
    {
        $expenseData = [
            'title' => 'Test Expense',
            'amount' => -100.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors('amount');
    }

    public function test_expense_amount_cannot_be_zero()
    {
        $expenseData = [
            'title' => 'Test Expense',
            'amount' => 0,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors('amount');
    }

    public function test_expense_requires_valid_expense_date()
    {
        $expenseData = [
            'title' => 'Test Expense',
            'amount' => 1000.00,
            'expense_date' => 'invalid-date',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors('expense_date');
    }

    public function test_expense_requires_valid_category()
    {
        $expenseData = [
            'title' => 'Test Expense',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => 99999,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_receipt_validation_works()
    {
        $validFile = UploadedFile::fake()->create('receipt.pdf', 1024, 'application/pdf');
        $invalidFile = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

        $expenseData = [
            'title' => 'Test Expense with Mixed Receipts',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'receipts' => [$validFile, $invalidFile]
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors(['receipts.1']);
    }

    public function test_receipt_validation_allows_pdf()
    {
        $receipt = UploadedFile::fake()->create('receipt.pdf', 1024, 'application/pdf');

        $expenseData = [
            'title' => 'Test with PDF',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'receipts' => [$receipt]
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertRedirect();

        $expense = Expense::where('title', 'Test with PDF')->first();
        $this->assertNotNull($expense);
        $this->assertCount(1, $expense->receipts);
    }

    public function test_receipt_validation_requires_at_least_one_file()
    {
        $expenseData = [
            'title' => 'Test without Receipts',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'receipts' => []
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors(['receipts']);
    }

    public function test_receipt_validation_rejects_large_files()
    {
        $largeFile = UploadedFile::fake()->create('large.pdf', 3072, 'application/pdf');

        $expenseData = [
            'title' => 'Test with Large File',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'receipts' => [$largeFile]
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $response->assertSessionHasErrors(['receipts.0']);
    }

    public function test_expense_creation_notifies_managers()
    {
        $expenseData = [
            'title' => 'New Business Expense',
            'amount' => 5000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
            'description' => 'Client entertainment'
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $expense = Expense::where('title', 'New Business Expense')->first();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->manager->id,
            'expense_id' => $expense->id,
            'type' => 'info'
        ]);
    }

    public function test_expense_sets_default_status_pending()
    {
        $expenseData = [
            'title' => 'Pending Status Check',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->employee)
            ->post(route('expenses.store'), $expenseData);

        $expense = Expense::where('title', 'Pending Status Check')->first();
        $this->assertEquals('pending', $expense->status);
    }

    public function test_employee_has_permission_to_create_expense()
    {
        $this->assertTrue($this->employee->hasPermissionTo('expense.create'));
    }

    public function test_manager_can_view_created_expense()
    {
        $expense = Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $this->category->id,
            'title' => 'Test Expense for Manager View',
            'amount' => 1000.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test description',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->manager)
            ->get(route('expenses.index'));

        $response->assertStatus(200);
        $response->assertViewHas('expenses');
    }
}
