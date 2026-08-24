<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin BAA',   'username' => 'admin_baa',   'email' => 'baa@spkbeasiswa.ac.id',   'role' => 'baa'],
            ['name' => 'Admin BAPSI', 'username' => 'admin_bapsi', 'email' => 'bapsi@spkbeasiswa.ac.id', 'role' => 'bapsi'],
            ['name' => 'Admin LKM',   'username' => 'admin_lkm',   'email' => 'lkm@spkbeasiswa.ac.id',   'role' => 'lkm'],
        ];

        foreach ($users as $user) {
            User::create(array_merge($user, [
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]));
        }

        $this->command->info('Users seeded: admin_baa, admin_bapsi, admin_lkm (password: password)');
    }
}
