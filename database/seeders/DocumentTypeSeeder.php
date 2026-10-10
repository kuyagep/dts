<?php

namespace Database\Seeders;

use App\Models\DocumentType;

use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Memorandum Circular'],
            ['name' => 'Purchase Request'],
            ['name' => 'Disbursement Voucher'],
            ['name' => 'Special Order'],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(['name' => $type['name']]);
        }
    }
}
