<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction_types')->insert([
            [
                'name' => 'Transfer',
                'code' => 'transfer',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internet',
                'code' => 'internet',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Top up',
                'code' => 'top_up',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Receive',
                'code' => 'receive',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);    }
}
