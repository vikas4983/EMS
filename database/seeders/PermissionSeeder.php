<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Category
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            // Expense
            'expense.view',
            'expense.create',
            'expense.edit',
            'expense.delete',
            'expense.approve',
            'expense.reject',
            'expense.comment',

            // Report 
            'report.view',
            'report.generate',

          
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
