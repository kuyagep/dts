<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Memorandum Circular', 'code' => 'MC'],
            ['name' => 'Purchase Request', 'code' => 'PR'],
            ['name' => 'Disbursement Voucher', 'code' => 'DV'],
            ['name' => 'Special Order', 'code' => 'SO'],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(['code' => $type['code']], $type);
        }
    }
}
