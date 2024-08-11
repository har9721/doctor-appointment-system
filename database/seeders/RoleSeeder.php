<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = [
            ['roleName' => 'Admin', 'isActive' => 1],
            ['roleName' => 'Doctor', 'isActive' => 1],
            ['roleName' => 'User', 'isActive' => 1],
        ];

        Role::insert($role);
    }
}
