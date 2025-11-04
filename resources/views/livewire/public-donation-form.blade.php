<div class="space-y-4">
    @if($submitted)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800 font-semibold">Donasi Anda telah diterima!</p>
            <p class="text-green-700 text-sm mt-1">Bukti transfer Anda akan diverifikasi oleh tim kami.</p>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-4">
        <!-- Nama Donatur -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input
                type="text"
                wire:model="donor_name"
                placeholder="Nama Anda"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
            @error('donor_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input
                type="email"
                wire:model="donor_email"
                placeholder="email@example.com"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
            @error('donor_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Nomor Telepon -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
            <input
                type="tel"
                wire:model="donor_phone"
                placeholder="08123456789"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
            @error('donor_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Jumlah Donasi -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Donasi (Rp)</label>
            <input
                type="number"
                wire:model="amount"
                placeholder="100000"
                min="10000"
                step="1000"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
            @error('amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            <p class="text-xs text-gray-500 mt-1">Minimum donasi: Rp 10.000</p>
        </div>

        <!-- Catatan -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan (Opsional)</label>
            <textarea
                wire:model="notes"
                placeholder="Tulis pesan atau doa Anda di sini"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
            ></textarea>
            @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:opacity-50"
        >
            <span wire:loading.remove>Kirim Donasi</span>
            <span wire:loading>Memproses...</span>
        </button>

        <!-- Info -->
        <p class="text-xs text-gray-500 text-center mt-4">
            Privasi Anda terjaga. Data Anda hanya digunakan untuk keperluan donasi.
        </p>
    </form>
</div>
