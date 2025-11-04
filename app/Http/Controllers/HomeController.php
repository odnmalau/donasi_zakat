<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Distribution;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        // Get content dari settings
        $content = [
            'hero_title' => Setting::get('hero_title', 'Platform Donasi Zakat Online'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'Salurkan zakat Anda dengan mudah dan tepat sasaran'),
            'hero_cta_text' => Setting::get('hero_cta_text', 'Mulai Berdonasi'),
            'hero_image' => Setting::get('hero_image', null),

            'about_title' => Setting::get('about_title', 'Tentang Kami'),
            'about_content' => Setting::get('about_content', 'Kami adalah platform yang membantu Anda menyalurkan zakat dengan mudah dan transparan.'),

            'how_step1_title' => Setting::get('how_step1_title', 'Pilih Kampanye'),
            'how_step1_desc' => Setting::get('how_step1_desc', 'Pilih kampanye zakat yang Anda ingin dukung'),

            'how_step2_title' => Setting::get('how_step2_title', 'Tentukan Jumlah'),
            'how_step2_desc' => Setting::get('how_step2_desc', 'Tentukan jumlah zakat yang ingin Anda sumbangkan'),

            'how_step3_title' => Setting::get('how_step3_title', 'Verifikasi & Selesai'),
            'how_step3_desc' => Setting::get('how_step3_desc', 'Verifikasi pembayaran Anda dan zakat siap disalurkan'),
        ];

        // Get statistics
        $stats = [
            'total_campaigns' => Campaign::where('status', 'active')->count(),
            'total_donations' => Donation::where('status', 'verified')->sum('amount'),
            'total_distributed' => Distribution::where('status', 'distributed')->sum('amount'),
            'total_beneficiaries' => Distribution::where('status', 'distributed')->distinct('mustahik_id')->count('mustahik_id'),
        ];

        // Get featured campaigns
        $campaigns = Campaign::where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        // Get testimonies (hardcoded untuk now, bisa di-move ke database nanti)
        $testimonies = [
            [
                'name' => 'Budi Santoso',
                'message' => 'Sangat mudah menggunakan platform ini. Zakat saya langsung terverifikasi dan tersalurkan.',
                'image' => 'https://ui-avatars.com/api/?name=Budi+Santoso',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'message' => 'Transparansi yang bagus. Saya bisa melihat kampanye apa yang didukung dana saya.',
                'image' => 'https://ui-avatars.com/api/?name=Siti+Nurhaliza',
            ],
            [
                'name' => 'Ahmad Wijaya',
                'message' => 'Penyaluran zakat jadi lebih efektif dan terpercaya. Rekomendasi untuk semua umat.',
                'image' => 'https://ui-avatars.com/api/?name=Ahmad+Wijaya',
            ],
        ];

        return view('landing.index', compact('content', 'stats', 'campaigns', 'testimonies'));
    }
}
