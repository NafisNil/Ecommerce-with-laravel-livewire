<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Placeholder; // Added missing import
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        // Wrapped components inside an array [ ... ]
        return $schema->components([
            Tabs::make('Product Details')->columnSpanFull()->tabs([
                Tab::make('Basic Information')
                    ->icon(Heroicon::InformationCircle)
                    ->schema([
                        Section::make('Product Details')
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                Select::make('category_id')->required()->relationship('category', 'name')->preload()->searchable()->createOptionForm([
                                    TextInput::make('name')->required(),
                                    TextInput::make('slug')->unique(ignoreRecord: true)->readOnly()->visibleOn('edit'),
                                ]),
                                Select::make('brand_id')->required()->relationship('brand', 'name')->preload()->searchable()->createOptionForm([
                                    TextInput::make('name')->required(),
                                    TextInput::make('slug')->unique(ignoreRecord: true)->readOnly()->visibleOn('edit'),
                                ]),
                                TextInput::make('name')->required(),
                                TextInput::make('slug')->required()->visible(fn(string $operation) => in_array($operation, ['edit']))->unique(ignoreRecord: true)
                            ]),

                        Section::make('Product Description')
                            ->columnSpanFull()->columns(2)
                            ->schema([
                                Textarea::make('short_description')->default(null)->columnSpanFull(),
                                RichEditor::make('description')->default(null)->columnSpanFull(),
                            ]),
                    ]),
                Tab::make('Pricing and Inventory')->icon(Heroicon::CurrencyDollar)->schema([
                    Section::make('Pricing')->columns(2)->schema([
                        TextInput::make('sku')->label('SKU')->required()->unique(ignoreRecord: true)->helperText('Stock Keeping Unit - unique identifier for the product')->default(fn()=>'SKU_'.strtoupper(uniqid())),
                        TextInput::make('price')->required()->numeric()->prefix('$')->minValue(0)->step(0.01)->helperText('Base price of the product'),
                        TextInput::make('compare_price')->numeric()->default(null)->prefix('$')->minValue(0)->step(0.01)->helperText('Compare at price for the product'),
                        TextInput::make('cost_price')->numeric()->default(null)->prefix('$')->minValue(0)->step(0.01)->helperText('Cost price of the product'),
                    ]),

                    Section::make('Inventory')->columns(2)->schema([
                        Toggle::make('manage_stock')->default(true)->live()->helperText('Enable stock management for this product'),
                        TextInput::make('stock_quantity')->required(fn (callable $get) => $get('manage_stock'))->disabled(fn (callable $get) => !$get('manage_stock'))->numeric()->default(0),
                        TextInput::make('stock_threshold')->label('Low Stock Threshold')->numeric()->default(0)->minValue(0)->helperText('Notify when stock quantity falls below this threshold'),
                        ToggleButtons::make('stock_status')->options([
                            'in_stock' => 'In Stock',
                            'on_backorder' => 'On Backorder',
                            'out_of_stock' => 'Out of Stock',
                        ])->required()->default('in_stock'),
                        TextInput::make('weight')->label('Weight(Kg)')->numeric()->default(null),
                    ]),
                ]),
                Tab::make('Images')->icon(Heroicon::Photo)->schema([
                    Section::make('Product Images')->columns(1)->description('Upload images for the product')->schema([
                        FileUpload::make('images')
                            ->label('Product Images')
                            ->multiple()
                            ->image()
                            ->directory('product-images')->imageEditor()->maxSize(1024 * 5)
                            ->acceptedFileTypes(['image/jpeg','image/jpg', 'image/png', 'image/gif'])->reorderable()->columnSpanFull()
                            ->helperText('Upload product images. You can upload multiple images.')
                            ->dehydrated(false),
                    ]),
                ]),
                Tab::make("Product Variant")->icon(Heroicon::Squares2x2)->schema([
                    Section::make('Variant Management')->columns(1)->description('Manage product variants')->schema([
                        Toggle::make('has_variants')->required()->live()->helperText('Enable this option if the product has multiple variants (e.g., different sizes, colors)'),
                        Section::make('Product Variants')->description('Add variants')
                        ->schema([
                                                    Repeater::make('variants')->relationship('variants')->schema([
                            TextInput::make('name')->required(),
                            KeyValue::make('options'),
                            TextInput::make('sku')->required()->unique(ignoreRecord: true)->helperText('Stock Keeping Unit - unique identifier for the variant')->default(fn()=>'VAR_'.strtoupper(uniqid())),

                            TextInput::make('price')->required()->numeric()->prefix('$')->minValue(0)->step(0.01)->helperText('Price of the variant'),
                            TextInput::make('compare_price')->numeric()->default(null)->prefix('$')->minValue(0)->step(0.01)->helperText('Compare at price for the variant'),

                            TextInput::make('stock_quantity')->required()->numeric()->default(0)->minValue(0)->helperText('Stock quantity for the variant'),
                            ToggleButtons::make('stock_status')->options([
                                'in_stock' => 'In Stock',
                                'on_backorder' => 'On Backorder',
                                'out_of_stock' => 'Out of Stock',
                            ])->required()->default('in_stock'),

                            Toggle::make('is_active')->required()->default(true),
                        ])->columnSpanFull()->defaultItems(0)->collapsible()->itemLabel(fn($state) => $state['name'] ?? 'New Variant')->addActionLabel('Add Variant'),
                        ])->visible(fn(callable $get) => $get('has_variants')),
                    ]),
                ]),
                Tab::make('Settings')->icon(Heroicon::Cog)->schema([
                    Section::make('Product Settings')->columns(2)->schema([
                        Toggle::make('is_active')->required(),
                        Toggle::make('is_featured')->required(),
                    ]),
                    Section::make('statistics')->columns(2)->schema([
                        Placeholder::make('view_count')->label('View Count')->content(fn($record) => $record ? $record->view_count : '0'),
                        Placeholder::make('created_at')->label('Created At')->content(fn($record) => $record ? $record->created_at->format('Y-m-d H:i:s') : 'N/A'),
                    ]),
                ]),
                Tab::make('SEO')->icon(Heroicon::MagnifyingGlass)->schema([
                    Section::make('Search Engine Optimization')->columns(1)->schema([
                        TextInput::make('meta_title')->default(null),
                        Textarea::make('meta_description')->default(null)->columnSpanFull(),
                    ]),
                ]),
            ]), // End of Tabs

            // These fields sit below the main Tab container


        ]);
    }
}