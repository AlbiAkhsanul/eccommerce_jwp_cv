<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;

class TransactionDetailSeeder extends Seeder
{
    public function run(): void
    {
        TransactionDetail::factory()->count(10)->create();
    }
}
