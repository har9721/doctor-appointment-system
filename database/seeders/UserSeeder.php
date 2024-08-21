<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'harshal',
            'last_name' => 'chaudhary',
            'email' => 'harshal@gmail.com',
            'password' => Hash::make('12345678'),
            'mobile' => '8475784578',
            'role_ID' => 1,
        ]);
    }
}
