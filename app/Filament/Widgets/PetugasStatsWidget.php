<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Distribution;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PetugasStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalDonations = Donation::where('status', 'verified')->sum('amount');
        $pendingDonations = Donation::where('status', 'pending')->count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $totalDistributed = Distribution::where('status', 'distributed')->sum('amount');

        return [
            Stat::make('Total Donasi Terverifikasi', 'Rp ' . number_format($totalDonations, 0, ',', '.'))
                ->description('Dana yang sudah diterima')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Donasi Menunggu Verifikasi', $pendingDonations)
                ->description('Butuh tindakan Anda')
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle'),
            Stat::make('Kampanye Aktif', $activeCampaigns)
                ->description('Sedang berjalan')
                ->color('info')
                ->icon('heroicon-o-flag'),
            Stat::make('Total Disalurkan', 'Rp ' . number_format($totalDistributed, 0, ',', '.'))
                ->description('Kepada penerima manfaat')
                ->color('primary')
                ->icon('heroicon-o-hand-raised'),
        ];
    }
}
