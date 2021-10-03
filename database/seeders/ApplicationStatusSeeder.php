<?php

namespace Database\Seeders;

use App\Models\ApplicationStatus;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'Принято'
            ],
            [
                'name' => 'Отклонено'
            ],
        ];

        ApplicationStatus::insert($statuses);
    }
}
