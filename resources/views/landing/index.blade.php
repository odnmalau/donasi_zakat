<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['hero_title'] }} - Platform Donasi Zakat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-900">
                Donasi Zakat
            </div>
            <div class="hidden md:flex gap-8">
                <a href="#home" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Beranda</a>
                <a href="#how" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Cara Kerja</a>
                <a href="#campaigns" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Kampanye</a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('campaigns.index') }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
                    Lihat Kampanye
                </a>
                <a href="/admin" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="bg-white pt-20 md:pt-32 pb-16">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $content['hero_title'] }}
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        {{ $content['hero_subtitle'] }}
                    </p>
                    <div class="flex gap-4 flex-wrap">
                        <a href="{{ route('campaigns.index') }}" class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition text-sm">
                            {{ $content['hero_cta_text'] }}
                        </a>
                        <a href="#campaigns" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition text-sm">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($stats['total_campaigns']) }}</div>
                        <div class="text-gray-600 text-sm">Kampanye Aktif</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                        <div class="text-xl font-bold text-green-600 mb-2 line-clamp-1">Rp {{ number_format($stats['total_donations'], 0, ',', '.') }}</div>
                        <div class="text-gray-600 text-sm">Total Donasi</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($stats['total_beneficiaries']) }}</div>
                        <div class="text-gray-600 text-sm">Penerima Manfaat</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-white border-t border-gray-200 py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                {{ $content['about_title'] }}
            </h2>
            <p class="text-lg text-gray-600 leading-relaxed">
                {{ $content['about_content'] }}
            </p>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how" class="bg-white py-16 md:py-24">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-16">
                Cara Kerja Platform
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="border border-gray-200 rounded-lg p-8 hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <span class="text-lg font-bold text-green-600">1</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">
                        {{ $content['how_step1_title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $content['how_step1_desc'] }}
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="border border-gray-200 rounded-lg p-8 hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <span class="text-lg font-bold text-green-600">2</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">
                        {{ $content['how_step2_title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $content['how_step2_desc'] }}
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="border border-gray-200 rounded-lg p-8 hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <span class="text-lg font-bold text-green-600">3</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">
                        {{ $content['how_step3_title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $content['how_step3_desc'] }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Campaigns -->
    <section id="campaigns" class="bg-white border-t border-gray-200 py-16 md:py-24">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-16">
                Kampanye Aktif
            </h2>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                @forelse($campaigns as $campaign)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-gray-300 transition">
                        <div class="bg-gray-100 h-48 flex items-center justify-center">
                            @if($campaign->image)
                                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-gray-400 text-4xl">📋</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $campaign->title }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $campaign->description }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mb-6">
                                @php
                                    $progress = $campaign->target_amount > 0
                                        ? ($campaign->collected_amount / $campaign->target_amount) * 100
                                        : 0;
                                @endphp
                                <div class="flex justify-between text-xs text-gray-600 mb-2">
                                    <span>Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                    <span>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                                </div>
                            </div>

                            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-center hover:bg-green-700 transition font-semibold text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-600">Tidak ada kampanye aktif saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center">
                <a href="{{ route('campaigns.index') }}" class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition text-sm">
                    Lihat Semua Kampanye
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-green-600 text-white py-16">
        <div class="max-w-4xl mx-auto text-center px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Mulai Berkontribusi Sekarang
            </h2>
            <p class="text-lg mb-8 text-green-50">
                Salurkan zakat Anda dengan mudah, transparan, dan tepat sasaran.
            </p>
            <a href="{{ route('campaigns.index') }}" class="inline-block px-8 py-3 bg-white text-green-600 rounded-lg font-semibold hover:bg-gray-50 transition text-sm">
                Lihat Kampanye Donasi
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-3 gap-12 mb-8">
                <div>
                    <h3 class="text-white font-bold mb-4 text-lg">Donasi Zakat</h3>
                    <p class="text-sm leading-relaxed">Platform penyaluran zakat yang transparan, aman, dan mudah digunakan.</p>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4 text-lg">Navigasi</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                        <li><a href="{{ route('campaigns.index') }}" class="hover:text-white">Kampanye</a></li>
                        <li><a href="/admin" class="hover:text-white">Admin Panel</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4 text-lg">Kontak</h3>
                    <ul class="space-y-2 text-sm">
                        <li>Email: info@donasizakat.id</li>
                        <li>Telepon: +62 21 XXXX XXXX</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; 2025 Platform Donasi Zakat. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
