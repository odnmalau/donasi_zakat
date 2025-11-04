<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Pengaturan Konten')
                    ->columns(1)
                    ->schema([
                        TextInput::make('key')
                            ->label('Kunci Pengaturan')
                            ->required()
                            ->unique('settings', 'key', ignoreRecord: true)
                            ->helperText('Contoh: hero_title, hero_subtitle, etc.'),
                        Select::make('type')
                            ->label('Tipe Konten')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea (Long Text)',
                                'image' => 'Image',
                                'json' => 'JSON (Advanced)',
                            ])
                            ->default('text')
                            ->required(),
                        TextInput::make('value')
                            ->label('Nilai')
                            ->columnSpanFull()
                            ->visible(fn($get) => $get('type') === 'text'),
                        Textarea::make('value')
                            ->label('Nilai')
                            ->columnSpanFull()
                            ->visible(fn($get) => $get('type') === 'textarea'),
                        FileUpload::make('value')
                            ->label('Upload Gambar')
                            ->image()
                            ->directory('settings')
                            ->columnSpanFull()
                            ->visible(fn($get) => $get('type') === 'image'),
                        Textarea::make('value')
                            ->label('JSON Value')
                            ->columnSpanFull()
                            ->visible(fn($get) => $get('type') === 'json'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->helperText('Penjelasan untuk admin tentang field ini'),
                    ]),
            ]);
    }
}
