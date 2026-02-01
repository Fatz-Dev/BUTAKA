<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Visitors hari ini dengan berbagai status
        Visitor::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi.santoso@email.com',
            'purpose' => 'Meeting dengan Direktur',
            'host_name' => 'Pak Ahmad',
            'institution' => 'PT Maju Jaya',
            'status' => 'berkunjung',
            'check_in_time' => $today->copy()->setTime(9, 30),
            'check_out_time' => null,
        ]);

        Visitor::create([
            'name' => 'Siti Aminah',
            'phone' => '082345678901',
            'email' => 'siti.aminah@email.com',
            'purpose' => 'Pengiriman Dokumen',
            'host_name' => 'Bu Dewi',
            'institution' => 'CV Berkah Abadi',
            'status' => 'selesai',
            'check_in_time' => $today->copy()->setTime(8, 0),
            'check_out_time' => $today->copy()->setTime(8, 45),
        ]);

        Visitor::create([
            'name' => 'Ahmad Fauzi',
            'phone' => '083456789012',
            'email' => null,
            'purpose' => 'Konsultasi',
            'host_name' => 'Pak Rudi',
            'institution' => null,
            'status' => 'menunggu',
            'check_in_time' => $today->copy()->setTime(10, 15),
            'check_out_time' => null,
        ]);

        Visitor::create([
            'name' => 'Dewi Lestari',
            'phone' => '084567890123',
            'email' => 'dewi.lestari@company.com',
            'purpose' => 'Interview Kerja',
            'host_name' => 'HRD',
            'institution' => null,
            'status' => 'menunggu',
            'check_in_time' => $today->copy()->setTime(10, 30),
            'check_out_time' => null,
        ]);

        // Visitors kemarin (sudah selesai semua)
        Visitor::create([
            'name' => 'Rahmat Hidayat',
            'phone' => '085678901234',
            'email' => 'rahmat.h@gmail.com',
            'purpose' => 'Presentasi Produk',
            'host_name' => 'Tim Marketing',
            'institution' => 'PT Teknologi Indonesia',
            'status' => 'selesai',
            'check_in_time' => $yesterday->copy()->setTime(14, 0),
            'check_out_time' => $yesterday->copy()->setTime(16, 30),
        ]);

        Visitor::create([
            'name' => 'Rina Wulandari',
            'phone' => '086789012345',
            'email' => null,
            'purpose' => 'Pengambilan Barang',
            'host_name' => 'Gudang',
            'institution' => 'Toko Sejahtera',
            'status' => 'selesai',
            'check_in_time' => $yesterday->copy()->setTime(11, 0),
            'check_out_time' => $yesterday->copy()->setTime(11, 30),
        ]);

        Visitor::create([
            'name' => 'Eko Prasetyo',
            'phone' => '087890123456',
            'email' => 'eko.prasetyo@mail.com',
            'purpose' => 'Audit',
            'host_name' => 'Tim Keuangan',
            'institution' => 'Kantor Akuntan Publik ABC',
            'status' => 'selesai',
            'check_in_time' => $yesterday->copy()->setTime(9, 0),
            'check_out_time' => $yesterday->copy()->setTime(17, 0),
        ]);
    }
}
