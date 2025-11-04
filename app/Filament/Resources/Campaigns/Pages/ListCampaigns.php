<?php

namespace App\Filament\Resources\Campaigns\Pages;

use App\Exports\CampaignExport;
use App\Filament\Resources\Campaigns\CampaignResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListCampaigns extends ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new CampaignExport, 'campaigns-'.date('Y-m-d-H-i-s').'.xlsx');
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $campaigns = \App\Models\Campaign::all();
                    $pdf = Pdf::loadView('exports.campaigns-pdf', compact('campaigns'));

                    return $pdf->download('campaigns-'.date('Y-m-d-H-i-s').'.pdf');
                }),
            CreateAction::make(),
        ];
    }
}
