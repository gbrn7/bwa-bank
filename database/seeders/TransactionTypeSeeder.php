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
                'thumbnail' => 'tranfer.png',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internet',
                'code' => 'internet',
                'thumbnail' => 'internet.png',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Top up',
                'code' => 'top_up',
                'thumbnail' => 'top-up.png',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Receive',
                'code' => 'receive',
                'thumbnail' => 'receive.png',
                'action' => 'dr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);    }
}
