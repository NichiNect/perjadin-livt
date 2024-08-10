<?php

namespace Database\Seeders;

use App\Models\City;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Surabaya',
                'province_id' => 35,
                'island' => 'Jawa',
                'latitude' => -7.3025898,
                'longitude' => 112.7218155,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Madura',
                'province_id' => 35,
                'island' => 'Madura',
                'latitude' => -7.1227159,
                'longitude' => 112.7130766,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Madiun',
                'province_id' => 35,
                'island' => 'Jawa',
                'latitude' => -7.6289007,
                'longitude' => 111.5137394,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Jombang',
                'province_id' => 35,
                'island' => 'Jawa',
                'latitude' => -7.5571246,
                'longitude' => 112.2328344,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Malang',
                'province_id' => 35,
                'island' => 'Jawa',
                'latitude' => -7.9534027,
                'longitude' => 112.6126363,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Jember',
                'province_id' => 35,
                'island' => 'Jawa',
                'latitude' => -8.1977406,
                'longitude' => 113.6906101,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Solo',
                'province_id' => 33,
                'island' => 'Jawa',
                'latitude' => -7.5653271,
                'longitude' => 110.8534737,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Yogyakarta',
                'province_id' => 33,
                'island' => 'Jawa',
                'latitude' => -7.8038239,
                'longitude' => 110.3602964,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Klaten',
                'province_id' => 33,
                'island' => 'Jawa',
                'latitude' => -7.7394011,
                'longitude' => 110.4340333,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Semarang',
                'province_id' => 33,
                'island' => 'Jawa',
                'latitude' => -6.9911878,
                'longitude' => 110.4205947,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Bogor',
                'province_id' => 32,
                'island' => 'Jawa',
                'latitude' => -6.5975969,
                'longitude' => 106.7948375,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Cianjur',
                'province_id' => 32,
                'island' => 'Jawa',
                'latitude' => -6.8222478,
                'longitude' => 107.1391873,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Kuningan',
                'province_id' => 32,
                'island' => 'Jawa',
                'latitude' => -6.9774506,
                'longitude' => 108.4847827,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Bekasi',
                'province_id' => 32,
                'island' => 'Jawa',
                'latitude' => -6.2413343,
                'longitude' => 106.9791986,
                'is_foreign_country' => false,
                'created_at' => Carbon::now()
            ],
        ];

        City::insert($data);
    }
}
