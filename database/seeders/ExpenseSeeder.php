<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
       
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Expense::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employee = User::where('email', 'employee@employee.com')->first();
        $manager = User::where('email', 'manager@manager.com')->first();

        $categories = Category::pluck('id')->toArray();

        if (!$employee || empty($categories)) {
            $this->command->error('Users or Categories not found! Please run TestDatabaseSeeder first.');
            return;
        }

        // Pending Expenses
        $this->createExpenses(
            count: 10,
            status: 'pending',
            userId: $employee->id,
            categories: $categories
        );

        // Approved Expenses
        $this->createExpenses(
            count: 5,
            status: 'approved',
            userId: $employee->id,
            categories: $categories,
            managerComment: 'Approved',
            managerId: $manager?->id
        );

        // Rejected Expenses
        $this->createExpenses(
            count: 3,
            status: 'rejected',
            userId: $employee->id,
            categories: $categories,
            managerComment: 'Rejected',
            managerId: $manager?->id
        );

        // Fixed Expenses
        $this->seedSpecificExpenses($employee->id);

        $this->command->info('Expenses seeded successfully!');
        $this->command->info('Total Expenses: ' . Expense::count());
    }

    /**
     * Create multiple expenses.
     */
    private function createExpenses(
        int $count,
        string $status,
        int $userId,
        array $categories,
        ?string $managerComment = null,
        ?int $managerId = null
    ): void {
        for ($i = 1; $i <= $count; $i++) {

            Expense::create([
                'title' => ucfirst($status) . " Expense {$i}",
                'amount' => fake()->randomFloat(2, 100, 1000),
                'expense_date' => fake()->dateTimeBetween('-30 days', 'now'),
                'description' => fake()->sentence(),
                'status' => $status,
                'user_id' => $userId,
                'category_id' => fake()->randomElement($categories),
                'manager_comment' => $managerComment,
     
            ]);
        }
    }

    /**
     * Seed fixed expenses.
     */
    private function seedSpecificExpenses(int $userId): void
    {
        $travel = Category::where('name', 'Travel')->first();
        $office = Category::where('name', 'Office Supplies')->first();

        if ($travel) {
            Expense::create([
                'title' => 'International Flight',
                'amount' => 45000,
                'expense_date' => '2026-06-20',
                'description' => 'Business trip to London',
                'status' => 'approved',
                'user_id' => $userId,
                'category_id' => $travel->id,
                'manager_comment' => 'Approved',
            ]);
        }

        if ($office) {
            Expense::create([
                'title' => 'Office Stationery',
                'amount' => 5000,
                'expense_date' => '2026-06-22',
                'description' => 'Printer paper, pens and notebooks',
                'status' => 'pending',
                'user_id' => $userId,
                'category_id' => $office->id,
            ]);
        }
    }
}
