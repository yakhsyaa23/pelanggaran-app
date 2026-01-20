<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data role dari database
        $adminRole = Role::where('role_name', 'admin')->first();
        $guruRole = Role::where('role_name', 'guru')->first();
        $userRole = Role::where('role_name', 'user')->first();

        // User Admin
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@sekolah.com'],
                [
                    'name' => 'Admin Sekolah',
                    'password' => Hash::make('password'), 
                    'role_id' => $adminRole->id,
                ]
            );
        }

        // Guru
        if ($guruRole) {
            User::firstOrCreate(
                ['email' => 'guru@sekolah.com'],
                [
                    'name' => 'Guru',
                    'password' => Hash::make('password'),
                    'role_id' => $guruRole->id,
                ]
            );
        }

        // User (Orang Tua Siswa)
        if ($userRole) {
            User::firstOrCreate(
                ['email' => 'orangtua@sekolah.com'],
                [
                    'name' => 'Orang Tua',
                    'password' => Hash::make('password'),
                    'role_id' => $userRole->id,
                ]
            );
        }
    }
}
