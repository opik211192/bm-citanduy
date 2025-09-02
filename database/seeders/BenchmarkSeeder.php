<?php

namespace Database\Seeders;

use App\Models\Benchmark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BenchmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 100; $i++) {
            Benchmark::create([
                'kode_bm' => 'BM' . $faker->unique()->randomNumber(),
                'no_registrasi' => 'REG' . $faker->unique()->randomNumber(),
                'nama_pekerjaan' => $faker->sentence(),
                'jenis_pekerjaan' => $faker->randomElement(['bendungan', 'embung', 'bendung']),
                'province_id' => 9, // kode provinsi Jawa Barat (kalau sesuai sistemmu)
                'city_id' => $faker->numberBetween(1, 10),
                'district_id' => $faker->numberBetween(1, 10),
                'village_id' => $faker->numberBetween(1, 10),
                'utm_x' => $faker->randomFloat(6, 500000, 700000), // perkiraan UTM X Jawa Barat
                'utm_y' => $faker->randomFloat(6, 9200000, 9400000), // UTM Y-nya juga dibatasi
                'lat' => $faker->randomFloat(6, -7.8, -6.0), // lintang Jawa Barat
                'long' => $faker->randomFloat(6, 106.0, 108.5), // bujur Jawa Barat
                'zone' => 48, // zona UTM Jawa Barat
                'tinggi_orthometrik' => $faker->numberBetween(50, 200),
                'tinggi_elipsoid' => $faker->numberBetween(50, 200),
                'keterangan' => $faker->sentence(),
            ]);
        }
    }
}
