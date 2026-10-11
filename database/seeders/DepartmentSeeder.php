<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Office of the Schools Division Superintendent', 'code' => 'OSDS'],
            ['name' => 'School Governance and Operations Division', 'code' => 'SGOD'],
            ['name' => 'Bansalan East', 'code' => 'BANE'],
            ['name' => 'Bansalan West', 'code' => 'BANW'],
            ['name' => 'Hagonoy I', 'code' => 'HAGI'],
            ['name' => 'Hagonoy II', 'code' => 'HAGII'],
            ['name' => 'Kiblawan North', 'code' => 'KIBN'],
            ['name' => 'Kiblawan South', 'code' => 'KIBS'],
            ['name' => 'Matanao I', 'code' => 'MATI'],
            ['name' => 'Matanao II', 'code' => 'MATII'],
            ['name' => 'Magsaysay North', 'code' => 'MAGN'],
            ['name' => 'Magsaysay South', 'code' => 'MAGS'],
            ['name' => 'Malalag', 'code' => 'MAL'],
            ['name' => 'Padada', 'code' => 'PAD'],
            ['name' => 'Sta. Cruz North', 'code' => 'STAN'],
            ['name' => 'Sta. Cruz South', 'code' => 'STAS'],
            ['name' => 'Sulop', 'code' => 'SUL'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
