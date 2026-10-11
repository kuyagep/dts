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
            ['name' => 'Activity Proposal'],
            ['name' => 'Application Documents'],
            ['name' => 'Authority to Travel'],
            ['name' => 'Bills'],
            ['name' => 'Central Office Issuances'],
            ['name' => 'Certificates'],
            ['name' => 'Communications (Letter, Etc.)'],
            ['name' => 'Daily Time Record (DTR)'],
            ['name' => 'Division Issuances'],
            ['name' => 'Funds'],
            ['name' => 'Leave'],
            ['name' => 'Letter'],
            ['name' => 'Liquidation / Reimbursement'],
            ['name' => 'Locator Slip'],
            ['name' => 'Permits'],
            ['name' => 'Procurements'],
            ['name' => 'Provident Fund Application'],
            ['name' => 'Regional Issuances'],
            ['name' => 'Reinstatement'],
            ['name' => 'Reports'],
            ['name' => 'Request for Cash Advance'],
            ['name' => 'Travel Abroad'],
            ['name' => 'Others'],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(['name' => $type['name']]);
        }
    }
}
