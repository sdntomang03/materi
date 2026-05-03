<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cache roles dan permissions Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'siswa']);

        // 3. Buat User Admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'), // password default: password
        ]);
        $admin->assignRole('admin');

        // 4. Buat User Operator
        $operator = User::create([
            'name' => 'Operator Sekolah',
            'email' => 'operator@mail.com',
            'password' => Hash::make('password'),
        ]);
        $operator->assignRole('operator');

        // 5. Buat User Guru
        $guru = User::create([
            'name' => 'Bapak Budi (Guru Math)',
            'email' => 'guru@mail.com',
            'password' => Hash::make('password'),
        ]);
        $guru->assignRole('guru');

        // 6. Buat User Siswa
        $siswa = User::create([
            'name' => 'Andi Pratama',
            'email' => 'siswa@mail.com',
            'password' => Hash::make('password'),
        ]);
        $siswa->assignRole('siswa');
    }
}
