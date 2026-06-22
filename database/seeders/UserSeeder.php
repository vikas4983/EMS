<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('password'),
            ],
        );

        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@manager.com'],
            [
                'name' => 'manager',
                'password' => Hash::make('password'),
            ],
        );

        $manager->assignRole('manager');

        $employee = User::firstOrCreate(
            ['email' => 'employee@employee.com'],
            [
                'name' => 'employee',
                'password' => Hash::make('password'),
            ],
        );

        $employee->assignRole('employee');
    }
}
