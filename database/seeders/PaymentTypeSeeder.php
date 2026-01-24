<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Transfer Bank', 'E-Wallet', 'Cash'] as $name) {
            PaymentType::firstOrCreate(['name' => $name]);
        }
    }
}
