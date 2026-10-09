<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fad = Department::where('code', 'OSDS')->first();

        if ($fad) {
            $offices = [
                ['name' => 'Accounting Section', 'code' => 'ACCT'],
                ['name' => 'Budget Section', 'code' => 'BDGT'],
                ['name' => 'Cashier Unit', 'code' => 'CASH'],
                ['name' => 'Human Resource Unit', 'code' => 'HR'],
            ];

            foreach ($offices as $off) {
                Office::firstOrCreate(
                    ['code' => $off['code']],
                    array_merge($off, ['department_id' => $fad->id])
                );
            }
        }
    }
}
