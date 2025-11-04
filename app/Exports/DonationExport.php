<?php

namespace App\Exports;

use App\Models\Donation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonationExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private ?string $status = null, private ?int $campaignId = null) {}

    public function query()
    {
        $query = Donation::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->campaignId) {
            $query->where('campaign_id', $this->campaignId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kampanye',
            'Nama Donatur',
            'Email',
            'Nomor Telepon',
            'Jumlah Donasi',
            'Status',
            'Diverifikasi Oleh',
            'Diverifikasi Pada',
            'Alasan Penolakan',
            'Donasi Pada',
        ];
    }

    public function map($donation): array
    {
        return [
            $donation->id,
            $donation->campaign?->title ?? '-',
            $donation->donor_name,
            $donation->donor_email,
            $donation->donor_phone,
            'Rp '.number_format($donation->amount, 0, ',', '.'),
            $donation->status,
            $donation->verifiedBy?->name ?? '-',
            $donation->verified_at ? $donation->verified_at->format('d/m/Y H:i') : '-',
            $donation->rejection_reason ?? '-',
            $donation->created_at->format('d/m/Y H:i'),
        ];
    }
}
