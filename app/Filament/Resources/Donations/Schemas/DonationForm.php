<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Donatur')
                    ->columns(2)
                    ->schema([
                        TextInput::make('donor_name')
                            ->label('Nama Donatur')
                            ->disabled(),
                        TextInput::make('donor_email')
                            ->label('Email Donatur')
                            ->email()
                            ->disabled(),
                        TextInput::make('donor_phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->disabled(),
                        Select::make('campaign_id')
                            ->label('Campaign')
                            ->relationship('campaign', 'title')
                            ->disabled(),
                    ]),
                Section::make('Donasi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Jumlah (Rp)')
                            ->numeric()
                            ->disabled()
                            ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Verified',
                                'rejected' => 'Rejected',
                            ])
                            ->disabled()
                            ->columnSpanFull(),
                        FileUpload::make('payment_proof_path')
                            ->label('Bukti Transfer')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
