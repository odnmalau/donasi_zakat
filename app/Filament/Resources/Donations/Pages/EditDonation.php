<?php

namespace App\Filament\Resources\Donations\Pages;

use App\Filament\Resources\Donations\DonationResource;
use App\Models\Donation;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\ActionSize;

class EditDonation extends EditRecord
{
    protected static string $resource = DonationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getVerifyAction(),
            $this->getRejectAction(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getVerifyAction(): Action
    {
        return Action::make('verify')
            ->label('Verifikasi Donasi')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->hidden(fn(Donation $record): bool => $record->status !== 'pending')
            ->action(function (Donation $record): void {
                $record->update([
                    'status' => 'verified',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'rejection_reason' => null,
                ]);

                // Update campaign collected_amount
                if ($record->campaign) {
                    $record->campaign->increment('collected_amount', $record->amount);
                }

                $this->notification()
                    ->success()
                    ->title('Donasi Terverifikasi')
                    ->body('Donasi telah berhasil diverifikasi.')
                    ->send();

                $this->redirect($this->getResource()::getUrl('index'));
            });
    }

    protected function getRejectAction(): Action
    {
        return Action::make('reject')
            ->label('Tolak Donasi')
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->hidden(fn(Donation $record): bool => $record->status !== 'pending')
            ->form([
                Textarea::make('rejection_reason')
                    ->label('Alasan Penolakan')
                    ->required()
                    ->maxLength(500),
            ])
            ->action(function (Donation $record, array $data): void {
                $record->update([
                    'status' => 'rejected',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'rejection_reason' => $data['rejection_reason'],
                ]);

                $this->notification()
                    ->danger()
                    ->title('Donasi Ditolak')
                    ->body('Donasi telah ditolak dengan alasan: ' . substr($data['rejection_reason'], 0, 50) . '...')
                    ->send();

                $this->redirect($this->getResource()::getUrl('index'));
            });
    }
}
