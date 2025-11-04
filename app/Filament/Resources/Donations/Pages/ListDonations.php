<?php

namespace App\Filament\Resources\Donations\Pages;

use App\Exports\DonationExport;
use App\Filament\Resources\Donations\DonationResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListDonations extends ListRecords
{
    protected static string $resource = DonationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new DonationExport, 'donations-'.date('Y-m-d-H-i-s').'.xlsx');
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $donations = \App\Models\Donation::all();
                    $pdf = Pdf::loadView('exports.donations-pdf', compact('donations'));

                    return $pdf->download('donations-'.date('Y-m-d-H-i-s').'.pdf');
                }),
            CreateAction::make(),
        ];
    }
}
