<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Section
        Setting::set('hero_title', 'Platform Donasi Zakat Online Terpercaya', 'text', 'Judul utama halaman hero');
        Setting::set('hero_subtitle', 'Salurkan zakat Anda dengan mudah, transparan, dan tepat sasaran kepada yang membutuhkan', 'text', 'Subtitle halaman hero');
        Setting::set('hero_cta_text', '🎯 Mulai Berdonasi Sekarang', 'text', 'Tombol CTA di hero section');

        // About Section
        Setting::set('about_title', 'Tentang Platform Donasi Zakat', 'text', 'Judul section About');
        Setting::set('about_content', 'Kami adalah platform penyaluran zakat yang memudahkan Anda untuk menyalurkan zakat dengan cara yang aman, transparan, dan profesional. Dengan sistem verifikasi yang ketat dan pengelolaan dana yang baik, kami memastikan zakat Anda sampai ke tangan penerima manfaat yang tepat sesuai dengan syariat Islam.', 'textarea', 'Konten about');

        // How It Works Section
        Setting::set('how_step1_title', '📋 Pilih Kampanye', 'text', 'Judul langkah 1');
        Setting::set('how_step1_desc', 'Pilih kampanye zakat yang Anda ingin dukung dari berbagai kebutuhan masyarakat yang telah terverifikasi oleh tim kami.', 'text', 'Deskripsi langkah 1');

        Setting::set('how_step2_title', '💰 Tentukan Jumlah', 'text', 'Judul langkah 2');
        Setting::set('how_step2_desc', 'Tentukan jumlah zakat yang ingin Anda sumbangkan sesuai dengan kemampuan Anda minimal Rp 10.000.', 'text', 'Deskripsi langkah 2');

        Setting::set('how_step3_title', '✅ Verifikasi & Selesai', 'text', 'Judul langkah 3');
        Setting::set('how_step3_desc', 'Upload bukti transfer pembayaran Anda. Tim kami akan memverifikasi dan menyalurkan zakat Anda dalam waktu 1-3 hari kerja.', 'text', 'Deskripsi langkah 3');
    }
}
