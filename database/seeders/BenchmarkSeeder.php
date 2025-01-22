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
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Benchmark::create([
                'kode_bm' => 'BM' . $faker->unique()->randomNumber(),
                'no_registrasi' => 'REG' . $faker->unique()->randomNumber(),
                'nama_pekerjaan' => $faker->sentence(),
                'province_id' => $faker->numberBetween(1, 10),
                'city_id' => $faker->numberBetween(1, 10),
                'district_id' => $faker->numberBetween(1, 10),
                'village_id' => $faker->numberBetween(1, 10),
                'utm_x' => $faker->randomFloat(6, 0, 100),
                'utm_y' => $faker->randomFloat(6, 0, 100),
                'lat' => $faker->latitude,
                'long' => $faker->longitude,
                'zone' => $faker->numberBetween(48, 52),
                'tinggi_orthometrik' => $faker->numberBetween(50, 200),
                'tinggi_elipsoid' => $faker->numberBetween(50, 200),
                'keterangan' => $faker->sentence(),
            ]);
        }
    }
}
