<?php

namespace Database\Seeders;

use App\Models\Aset;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ambil ID acak dari database wilayah
        $provinceIds = DB::table('indonesia_provinces')->pluck('id')->toArray();
        $cityIds = DB::table('indonesia_cities')->pluck('id')->toArray();
        $districtIds = DB::table('indonesia_districts')->pluck('id')->toArray();
        $villageIds = DB::table('indonesia_villages')->pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            Aset::create([
                'nama_aset' => 'Aset ' . $faker->word(),
                'jenis_aset' => $faker->randomElement(['Embung', 'Bendung', 'Bendungan']),
                'no_registrasi' => 'REG-' . $faker->unique()->numerify('#####'),
                'kode_bmn' => 'BMN-' . $faker->unique()->numerify('######'),
                'province_id' => $faker->randomElement($provinceIds),
                'city_id' => $faker->randomElement($cityIds),
                'district_id' => $faker->randomElement($districtIds),
                'village_id' => $faker->randomElement($villageIds),
                'lat' => $faker->latitude(-11, 6),
                'long' => $faker->longitude(95, 141),
                'utm_x' => $faker->numerify('########'),
                'utm_y' => $faker->numerify('########'),
                'tahun_mulai_bangunan' => $faker->year(),
                'tahun_selesai_bangunan' => $faker->year(),
                'kondisi_bangunan' => $faker->randomElement(['Belum Terbangun', 'Terbangun']),
                'keterangan' => $faker->sentence(),
            ]);
        }

        //Embung cilentah 
        Aset::create([
            'nama_aset' => 'Cilentah',
            'jenis_aset' => 'Embung',
            'no_registrasi' => '06.05.19010501317',
            'kode_bmn' => '5.02.05.01.001.6',
            'province_id' => 12, 
            'city_id' => 178, 
            'district_id' => 2528, 
            'village_id' => 31228,
            'lat' => -7.5561583,
            'long' => 108.6819752,
            'utm_x' => '906449.39',
            'utm_y' => '9163049.91',
            'tahun_mulai_bangunan' => 2007,
            'tahun_selesai_bangunan' => 2007,
            'kondisi_bangunan' => 'Baik',
            'keterangan' => 'Beroperasi',
        ]);

         Aset::create([
            'nama_aset' => 'Cilentah',
            'jenis_aset' => 'Embung',
            'no_registrasi' => '06.05.19010501317',
            'kode_bmn' => '5.02.05.01.001.6',
            'province_id' => 12, 
            'city_id' => 178, 
            'district_id' => 2528, 
            'village_id' => 31228,
            'lat' => -7.5561583,
            'long' => 108.6819752,
            'utm_x' => '906449.39',
            'utm_y' => '9163049.91',
            'tahun_mulai_bangunan' => 2007,
            'tahun_selesai_bangunan' => 2007,
            'kondisi_bangunan' => 'Baik',
            'keterangan' => 'Beroperasi',
        ]);
        
    }
}
