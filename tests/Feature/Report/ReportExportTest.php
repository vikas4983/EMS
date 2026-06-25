<?php

namespace Tests\Feature\Report;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'employee', 'guard_name' => 'web']);

        Permission::create(['name' => 'expense.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'expense.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'report.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'report.generate', 'guard_name' => 'web']);

        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions(Permission::all());

        $employeeRole = Role::findByName('employee');
        $employeeRole->syncPermissions(['expense.view', 'expense.create']);

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com'
        ]);
        $this->admin->assignRole('admin');

        $this->employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@test.com'
        ]);
        $this->employee->assignRole('employee');
    }

    public function test_employee_cannot_export_report()
    {
        $category = Category::create(['name' => 'Travel', 'status' => 1]);

        Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $category->id,
            'title' => 'Test',
            'amount' => 100.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->employee)
            ->get('/expense-filter?download_csv=true');

        $response->assertStatus(403);
    }

    public function test_export_returns_error_when_no_records()
    {
        $response = $this->actingAs($this->admin)
            ->get('/expense-filter?download_csv=true');

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'No records available for export.');
    }

    public function test_csv_export_has_correct_filename_header()
    {
        $category = Category::create(['name' => 'Travel', 'status' => 1]);

        Expense::create([
            'user_id' => $this->employee->id,
            'category_id' => $category->id,
            'title' => 'Test',
            'amount' => 100.00,
            'expense_date' => now()->format('Y-m-d'),
            'description' => 'Test',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/expense-filter?download_csv=true');

        $response->assertStatus(200);
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertNotNull($disposition);
        $this->assertStringContainsString('attachment; filename=expense-report-', $disposition);
        $this->assertStringContainsString('.csv', $disposition);
    }
}
