<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')->columnSpanFull()->columns(2)
                     ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')->unique(ignoreRecord: true)->readOnly()
                    ->visibleOn('edit')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('logo')->disk('public')->directory('brands')->imageEditor()->preserveFilenames()->downloadable()
                    ->image()->maxSize(2048)
                    ->default(null),
                ]),
            Section::make('SEO Information')->columnSpanFull()->columns(2)
            ->schema([
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('website')
                    ->url()->placeholder('https://example.com')
                    ->default(null),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]),
            ]);
    }
}
