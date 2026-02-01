<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Feedback dengan rating tinggi
        Feedback::create([
            'name' => 'Budi Santoso',
            'institution' => 'PT Maju Jaya',
            'rating' => 5,
            'comment' => 'Pelayanan sangat memuaskan! Resepsionis sangat ramah dan proses check-in cepat.',
        ]);

        Feedback::create([
            'name' => 'Siti Aminah',
            'institution' => 'CV Berkah Abadi',
            'rating' => 5,
            'comment' => 'Sangat terbantu dengan sistem antrian yang rapi. Terima kasih!',
        ]);

        Feedback::create([
            'name' => 'Rahmat Hidayat',
            'institution' => 'PT Teknologi Indonesia',
            'rating' => 4,
            'comment' => 'Pelayanan bagus, ruang tunggu nyaman.',
        ]);

        // Feedback dengan rating sedang
        Feedback::create([
            'name' => 'Dewi Lestari',
            'institution' => null,
            'rating' => 3,
            'comment' => 'Cukup baik, tapi waktu tunggu agak lama.',
        ]);

        Feedback::create([
            'name' => 'Eko Prasetyo',
            'institution' => 'Kantor Akuntan Publik ABC',
            'rating' => 3,
            'comment' => 'Standar pelayanan sudah cukup baik.',
        ]);

        // Feedback dengan rating rendah
        Feedback::create([
            'name' => 'Ahmad Fauzi',
            'institution' => null,
            'rating' => 2,
            'comment' => 'Perlu ditingkatkan lagi pelayanannya.',
        ]);

        // Feedback tanpa komentar
        Feedback::create([
            'name' => 'Rina Wulandari',
            'institution' => 'Toko Sejahtera',
            'rating' => 4,
            'comment' => null,
        ]);

        // Feedback anonim
        Feedback::create([
            'name' => null,
            'institution' => null,
            'rating' => 5,
            'comment' => 'Mantap! Lanjutkan!',
        ]);
    }
}
