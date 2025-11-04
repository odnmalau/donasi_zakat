<?php

namespace App\Exports;

use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CampaignExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private ?string $status = null) {}

    public function query()
    {
        $query = Campaign::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Kategori',
            'Target Dana',
            'Dana Terkumpul',
            'Persentase',
            'Status',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Dibuat Pada',
        ];
    }

    public function map($campaign): array
    {
        $progress = $campaign->target_amount > 0
            ? ($campaign->collected_amount / $campaign->target_amount) * 100
            : 0;

        return [
            $campaign->id,
            $campaign->title,
            $campaign->category?->name ?? '-',
            'Rp '.number_format($campaign->target_amount, 0, ',', '.'),
            'Rp '.number_format($campaign->collected_amount, 0, ',', '.'),
            round($progress, 2).'%',
            $campaign->status,
            $campaign->start_date->format('d/m/Y'),
            $campaign->end_date->format('d/m/Y'),
            $campaign->created_at->format('d/m/Y H:i'),
        ];
    }
}
