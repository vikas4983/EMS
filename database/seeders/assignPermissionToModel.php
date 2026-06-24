<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class assignPermissionToModel extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        $manager = User::where('email', 'manager@manager.com')->first();
        $employee = User::where('email', 'employee@employee.com')->first();

       
        if ($admin) {
            $admin->givePermissionTo(Permission::all());
        }

        // MANAGER: Approve/Reject permissions 
        if ($manager) {
            $manager->givePermissionTo([
               'expense.approve',
                'expense.reject',
                'expense.comment',
            ]);
        }

        // EMPLOYEE: CRUD permissions 
        if ($employee) { 
            $employee->givePermissionTo([
                'expense.view',
                'expense.create',
                'expense.edit'
            ]);
        }
    }
}
