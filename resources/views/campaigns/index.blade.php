<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kampanye Donasi Zakat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-900">Donasi Zakat</div>
            <div class="hidden md:flex gap-8">
                <a href="/" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Beranda</a>
                <a href="/campaigns" class="text-gray-900 text-sm font-medium">Kampanye</a>
            </div>
            <div class="flex gap-3">
                <a href="/" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
                    Kembali
                </a>
                <a href="/admin" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-white border-b border-gray-200 py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Kampanye Donasi Zakat</h1>
            <p class="text-lg text-gray-600">Bergabunglah dengan kami dalam membantu sesama melalui program-program sosial yang telah terverifikasi</p>
        </div>
    </section>

    <!-- Campaigns Grid -->
    <section class="bg-white py-16 md:py-24">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            @if($campaigns->count() > 0)
                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    @foreach($campaigns as $campaign)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-gray-300 transition">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                @if($campaign->image)
                                    <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-gray-400 text-4xl">📋</div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $campaign->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ strip_tags($campaign->description) }}</p>

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

                                <!-- Dates -->
                                <div class="text-xs text-gray-500 mb-6">
                                    <p>{{ $campaign->start_date->format('d M Y') }} - {{ $campaign->end_date->format('d M Y') }}</p>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-center hover:bg-green-700 transition font-semibold text-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $campaigns->links() }}
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
                    <p class="text-gray-600">Tidak ada kampanye aktif saat ini.</p>
                </div>
            @endif
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
                        <li><a href="/" class="hover:text-white">Beranda</a></li>
                        <li><a href="/campaigns" class="hover:text-white">Kampanye</a></li>
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
