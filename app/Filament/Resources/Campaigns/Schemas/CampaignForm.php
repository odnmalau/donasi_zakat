<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kampanye')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Kampanye')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $state, \Filament\Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar Kampanye')
                            ->image()
                            ->directory('campaigns')
                            ->columnSpanFull(),
                    ]),
                Section::make('Target dan Status')
                    ->columns(2)
                    ->schema([
                        TextInput::make('target_amount')
                            ->label('Target Dana (Rp)')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->minValue(0),
                        TextInput::make('collected_amount')
                            ->label('Dana Terkumpul (Rp)')
                            ->numeric()
                            ->disabled()
                            ->prefix('Rp')
                            ->dehydrated(false),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Aktif',
                                'completed' => 'Selesai',
                                'archived' => 'Arsip',
                            ])
                            ->required(),
                    ]),
                Section::make('Tanggal')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Berakhir')
                            ->required(),
                    ]),
            ]);
    }
}
