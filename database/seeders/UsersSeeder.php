<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Roles;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesAdmin = Roles::where('name', 'admin')->first();
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password'=> Hash::make('password'),
            'role_id' => $rolesAdmin->id
          ]);

    }
}
