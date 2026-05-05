<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // Skip jika sudah ada data
        if (DB::table('wilayah_provinsi')->count() > 0) {
            return;
        }

        // 5 Provinsi sample
        $provinsi = [
            ['id' => '11', 'nama' => 'ACEH'],
            ['id' => '12', 'nama' => 'SUMATERA UTARA'],
            ['id' => '31', 'nama' => 'DKI JAKARTA'],
            ['id' => '32', 'nama' => 'JAWA BARAT'],
            ['id' => '33', 'nama' => 'JAWA TENGAH'],
            ['id' => '34', 'nama' => 'DI YOGYAKARTA'],
            ['id' => '35', 'nama' => 'JAWA TIMUR'],
            ['id' => '51', 'nama' => 'BALI'],
            ['id' => '73', 'nama' => 'KOTA MAKASSAR'],
        ];
        DB::table('wilayah_provinsi')->insert($provinsi);

        // Kota per provinsi
        $kota = [
            // DKI Jakarta
            ['id' => '3101', 'id_provinsi' => '31', 'nama' => 'KAB. ADM. KEPULAUAN SERIBU'],
            ['id' => '3171', 'id_provinsi' => '31', 'nama' => 'KOTA JAKARTA SELATAN'],
            ['id' => '3172', 'id_provinsi' => '31', 'nama' => 'KOTA JAKARTA TIMUR'],
            ['id' => '3173', 'id_provinsi' => '31', 'nama' => 'KOTA JAKARTA PUSAT'],
            ['id' => '3174', 'id_provinsi' => '31', 'nama' => 'KOTA JAKARTA BARAT'],
            ['id' => '3175', 'id_provinsi' => '31', 'nama' => 'KOTA JAKARTA UTARA'],
            // Jawa Barat
            ['id' => '3201', 'id_provinsi' => '32', 'nama' => 'KAB. BOGOR'],
            ['id' => '3202', 'id_provinsi' => '32', 'nama' => 'KAB. SUKABUMI'],
            ['id' => '3271', 'id_provinsi' => '32', 'nama' => 'KOTA BOGOR'],
            ['id' => '3273', 'id_provinsi' => '32', 'nama' => 'KOTA BANDUNG'],
            ['id' => '3276', 'id_provinsi' => '32', 'nama' => 'KOTA DEPOK'],
            ['id' => '3277', 'id_provinsi' => '32', 'nama' => 'KOTA CIMAHI'],
            // Jawa Tengah
            ['id' => '3301', 'id_provinsi' => '33', 'nama' => 'KAB. CILACAP'],
            ['id' => '3302', 'id_provinsi' => '33', 'nama' => 'KAB. BANYUMAS'],
            ['id' => '3371', 'id_provinsi' => '33', 'nama' => 'KOTA MAGELANG'],
            ['id' => '3374', 'id_provinsi' => '33', 'nama' => 'KOTA SEMARANG'],
            // DI Yogyakarta
            ['id' => '3401', 'id_provinsi' => '34', 'nama' => 'KAB. KULON PROGO'],
            ['id' => '3402', 'id_provinsi' => '34', 'nama' => 'KAB. BANTUL'],
            ['id' => '3404', 'id_provinsi' => '34', 'nama' => 'KAB. SLEMAN'],
            ['id' => '3471', 'id_provinsi' => '34', 'nama' => 'KOTA YOGYAKARTA'],
            // Jawa Timur
            ['id' => '3501', 'id_provinsi' => '35', 'nama' => 'KAB. PACITAN'],
            ['id' => '3502', 'id_provinsi' => '35', 'nama' => 'KAB. PONOROGO'],
            ['id' => '3578', 'id_provinsi' => '35', 'nama' => 'KOTA SURABAYA'],
            ['id' => '3573', 'id_provinsi' => '35', 'nama' => 'KOTA MALANG'],
            // Bali
            ['id' => '5101', 'id_provinsi' => '51', 'nama' => 'KAB. JEMBRANA'],
            ['id' => '5102', 'id_provinsi' => '51', 'nama' => 'KAB. TABANAN'],
            ['id' => '5103', 'id_provinsi' => '51', 'nama' => 'KAB. BADUNG'],
            ['id' => '5171', 'id_provinsi' => '51', 'nama' => 'KOTA DENPASAR'],
        ];
        DB::table('wilayah_kota')->insert($kota);

        // Kecamatan sample per kota
        $kecamatan = [
            // Jakarta Selatan (3171)
            ['id' => '317101', 'id_kota' => '3171', 'nama' => 'TEBET'],
            ['id' => '317102', 'id_kota' => '3171', 'nama' => 'SETIABUDI'],
            ['id' => '317103', 'id_kota' => '3171', 'nama' => 'MAMPANG PRAPATAN'],
            ['id' => '317104', 'id_kota' => '3171', 'nama' => 'PASAR MINGGU'],
            ['id' => '317105', 'id_kota' => '3171', 'nama' => 'JAGAKARSA'],
            // Kota Bandung (3273)
            ['id' => '327301', 'id_kota' => '3273', 'nama' => 'BANDUNG KULON'],
            ['id' => '327302', 'id_kota' => '3273', 'nama' => 'BABAKAN CIPARAY'],
            ['id' => '327303', 'id_kota' => '3273', 'nama' => 'BOJONGLOA KALER'],
            // Kota Yogyakarta (3471)
            ['id' => '347101', 'id_kota' => '3471', 'nama' => 'MANTRIJERON'],
            ['id' => '347102', 'id_kota' => '3471', 'nama' => 'KRATON'],
            ['id' => '347103', 'id_kota' => '3471', 'nama' => 'GONDOMANAN'],
            // Kota Surabaya (3578)
            ['id' => '357801', 'id_kota' => '3578', 'nama' => 'KARANG PILANG'],
            ['id' => '357802', 'id_kota' => '3578', 'nama' => 'JAMBANGAN'],
            ['id' => '357803', 'id_kota' => '3578', 'nama' => 'GAYUNGAN'],
        ];
        DB::table('wilayah_kecamatan')->insert($kecamatan);

        // Kelurahan sample
        $kelurahan = [
            // Tebet (317101)
            ['id' => '31710101', 'id_kecamatan' => '317101', 'nama' => 'TEBET BARAT'],
            ['id' => '31710102', 'id_kecamatan' => '317101', 'nama' => 'TEBET TIMUR'],
            ['id' => '31710103', 'id_kecamatan' => '317101', 'nama' => 'KEBON BARU'],
            // Setiabudi (317102)
            ['id' => '31710201', 'id_kecamatan' => '317102', 'nama' => 'MENTENG ATAS'],
            ['id' => '31710202', 'id_kecamatan' => '317102', 'nama' => 'PASAR MANGGIS'],
            // Mantrijeron (347101)
            ['id' => '34710101', 'id_kecamatan' => '347101', 'nama' => 'GEDONGKIWO'],
            ['id' => '34710102', 'id_kecamatan' => '347101', 'nama' => 'SURYODININGRATAN'],
            // Karang Pilang (357801)
            ['id' => '35780101', 'id_kecamatan' => '357801', 'nama' => 'KEDURUS'],
            ['id' => '35780102', 'id_kecamatan' => '357801', 'nama' => 'KARANG PILANG'],
        ];
        DB::table('wilayah_kelurahan')->insert($kelurahan);
    }
}
