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
            ['name' => 'Curriculum Implementation Division', 'code' => 'CID'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
