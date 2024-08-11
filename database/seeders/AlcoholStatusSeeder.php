<?php

namespace Database\Seeders;

use App\Models\AlcoholStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlcoholStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['statusName' => 'Never' ,'isActive' => 1],
            ['statusName' => 'Occasionally' ,'isActive' => 1],
            ['statusName' => 'Regularly' ,'isActive' => 1],
        ];
        
        AlcoholStatus::insert($status);
    }
}
