<?php

namespace App\Filament\Widgets;

use App\Models\Distribution;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MustahikStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $totalReceived = Distribution::where('mustahik_id', $user->id)
            ->where('status', 'distributed')->sum('amount');
        $distributionCount = Distribution::where('mustahik_id', $user->id)
            ->where('status', 'distributed')->count();

        return [
            Stat::make('Total Manfaat Diterima', 'Rp ' . number_format($totalReceived, 0, ',', '.'))
                ->description('Dari penyaluran zakat')
                ->color('success')
                ->icon('heroicon-o-gift'),
            Stat::make('Jumlah Penyaluran', $distributionCount)
                ->description('Kali menerima bantuan')
                ->color('info')
                ->icon('heroicon-o-chart-bar'),
        ];
    }
}
