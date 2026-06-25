<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::findByName('admin');
        $manager = Role::findByName('manager');
        $employee = Role::findByName('employee');

        
        $admin->syncPermissions(
            Permission::pluck('name')->toArray()
        );

        
        $manager->syncPermissions([
            'expense.approve',
            'expense.reject',
            'expense.comment',
        ]);

       
        $employee->syncPermissions([
            'expense.view',
            'expense.create',
            'expense.edit',
        ]);
    }
}
