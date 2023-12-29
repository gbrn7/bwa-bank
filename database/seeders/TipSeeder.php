<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tips')->insert([
            [
                'title' => 'Cara menyimpan uang yang baik',
                'thumbnail' => 'nabung.png',
                'url' => 'https://www.cnbcindonesia.com/lifestyle/20230906084552-33-469710/6-tips-menabung-ala-orang-jepang-duit-cepat-ngumpul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Prediksi Emas',
                'thumbnail' => 'emas.png',
                'url' => 'https://finance.detik.com/berita-ekonomi-bisnis/d-7112147/prediksi-harga-emas-2024-bisa-naik-di-atas-us-2-000-per-troy-ons',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Berita Saham',
                'thumbnail' => 'saham.png',
                'url' => 'https://market.bisnis.com/read/20231229/7/1728025/ihsg-berpeluang-ke-7380-akhir-2023-ajaib-rekomendasi-3-saham',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
