<?php

namespace Database\Seeders;

use App\Models\SmokingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmokingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['statusName' => 'Current' , 'isActive' => 1],
            ['statusName' => 'Former' , 'isActive' => 1],
            ['statusName' => 'Never' , 'isActive' => 1],
        ];

        SmokingStatus::insert($status);
    }
}
