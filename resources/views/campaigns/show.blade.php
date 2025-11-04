<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->title }} - Donasi Zakat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-900">Donasi Zakat</div>
            <div class="hidden md:flex gap-8">
                <a href="/" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Beranda</a>
                <a href="/campaigns" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Kampanye</a>
            </div>
            <div class="flex gap-3">
                <a href="/campaigns" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
                    Kembali
                </a>
                <a href="/admin" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 md:px-6 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Campaign Details -->
            <div class="md:col-span-2">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    @if($campaign->image)
                        <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-100 flex items-center justify-center">
                            <span class="text-gray-400">No Image</span>
                        </div>
                    @endif

                    <div class="p-8">
                        <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $campaign->title }}</h1>

                        <!-- Progress -->
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Target: Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                <span class="text-lg font-bold text-green-600">{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Terkumpul:</span>
                                <span class="font-bold text-green-600 text-xl">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Campaign Info -->
                        <div class="grid grid-cols-2 gap-4 mb-8 pb-8 border-b border-gray-200">
                            <div>
                                <p class="text-gray-600 text-sm">Mulai</p>
                                <p class="font-semibold text-gray-900">{{ $campaign->start_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Berakhir</p>
                                <p class="font-semibold text-gray-900">{{ $campaign->end_date->format('d M Y') }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Kampanye</h3>
                            <div class="text-gray-600 leading-relaxed">
                                {!! $campaign->description !!}
                            </div>
                        </div>

                        <!-- Recent Donations -->
                        @if($donations->count() > 0)
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Donatur Terbaru</h3>
                                <div class="space-y-4">
                                    @foreach($donations as $donation)
                                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $donation->donor_name }}</p>
                                                <p class="text-sm text-gray-500">{{ $donation->created_at->diffForHumans() }}</p>
                                            </div>
                                            <p class="font-bold text-green-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Donation Form Sidebar -->
            <div>
                <div class="border border-gray-200 rounded-lg p-8 sticky top-24">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Buat Donasi</h2>
                    @livewire('public-donation-form', ['campaign' => $campaign])
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 mt-16">
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

    @livewireScripts
</body>
</html>
