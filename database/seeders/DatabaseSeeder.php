<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => config('auth.defaults.guard', 'web'),
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'password' => 'admin',
            ]
        );

        if (! $admin->hasRole($role)) {
            $admin->assignRole($role);
        }
    }
}
