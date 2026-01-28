<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SchoolSetting;
use App\Models\Teacher;
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

        SchoolSetting::firstOrCreate(
            ['school_name' => 'Sekolah Dummy'],
            [
                'address' => 'Jl. Contoh No. 123',
                'academic_year' => '2025/2026',
                'semester' => 'Genap',
            ]
        );

        $teachers = [
            [
                'nip' => '198001012005011001',
                'nuptk' => '1234567890123456',
                'name' => 'Siti Nurhaliza',
                'gender' => 'P',
                'phone' => '081234567890',
                'status' => 'active',
            ],
            [
                'nip' => '197905152004021002',
                'nuptk' => '2234567890123456',
                'name' => 'Ahmad Fauzi',
                'gender' => 'L',
                'phone' => '081234567891',
                'status' => 'active',
            ],
            [
                'nip' => '198811202010011003',
                'nuptk' => '3234567890123456',
                'name' => 'Dewi Lestari',
                'gender' => 'P',
                'phone' => '081234567892',
                'status' => 'active',
            ],
            [
                'nip' => '198307252007011004',
                'nuptk' => '4234567890123456',
                'name' => 'Budi Santoso',
                'gender' => 'L',
                'phone' => '081234567893',
                'status' => 'active',
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::firstOrCreate(
                ['nip' => $teacher['nip']],
                $teacher
            );
        }

        $firstTeacher = Teacher::query()->orderBy('id')->first();
        if ($firstTeacher) {
            User::firstOrCreate(
                ['email' => 'guru1@mail.com'],
                [
                    'name' => 'Akun Guru',
                    'password' => 'guru',
                    'teacher_id' => $firstTeacher->id,
                ]
            );
        }
    }
}
