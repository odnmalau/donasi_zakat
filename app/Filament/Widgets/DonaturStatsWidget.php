<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DonaturStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $myDonations = Donation::where('user_id', $user->id)->sum('amount');
        $myDonationCount = Donation::where('user_id', $user->id)->count();
        $verifiedDonations = Donation::where('user_id', $user->id)
            ->where('status', 'verified')->count();

        return [
            Stat::make('Total Donasi Saya', 'Rp ' . number_format($myDonations, 0, ',', '.'))
                ->description('Seluruh kontribusi Anda')
                ->color('success')
                ->icon('heroicon-o-heart'),
            Stat::make('Jumlah Donasi', $myDonationCount)
                ->description('Kali Anda berdonasi')
                ->color('info')
                ->icon('heroicon-o-hand-raised'),
            Stat::make('Donasi Terverifikasi', $verifiedDonations)
                ->description('Sudah dikonfirmasi')
                ->color('primary')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
