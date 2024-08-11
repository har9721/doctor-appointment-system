<?php

namespace Database\Seeders;

use App\Models\MstGender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $gender = [
            ['gender' => 'Male','isActive' => 1],
            ['gender' => 'Female','isActive' => 1],
            ['gender' => 'Other','isActive' => 1],
        ];

        foreach ($gender as $value) {
            MstGender::insert($value);
        }
    }
}
