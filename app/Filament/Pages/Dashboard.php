<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DonaturStatsWidget;
use App\Filament\Widgets\MustahikStatsWidget;
use App\Filament\Widgets\PetugasStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        $user = auth()->user();
        $widgets = [];

        // Show all widgets for super admin
        if ($user->hasRole('super_admin')) {
            $widgets = [
                PetugasStatsWidget::class,
                DonaturStatsWidget::class,
                MustahikStatsWidget::class,
            ];
        }
        // Show petugas-specific widgets
        elseif ($user->hasRole('petugas_yayasan')) {
            $widgets = [
                PetugasStatsWidget::class,
            ];
        }
        // Show donatur-specific widgets
        elseif ($user->hasRole('donatur')) {
            $widgets = [
                DonaturStatsWidget::class,
            ];
        }
        // Show mustahik-specific widgets
        elseif ($user->hasRole('mustahik')) {
            $widgets = [
                MustahikStatsWidget::class,
            ];
        }

        return $widgets;
    }
}
