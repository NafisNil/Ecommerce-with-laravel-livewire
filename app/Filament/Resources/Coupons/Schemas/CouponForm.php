<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Information')->columns(2)->columnSpanFull()->schema([
                    TextInput::make('code')->unique(ignoreRecord: true)->required()->live(onBlur: true)->afterStateUpdated(fn($state, callable $set) => $set('code', strtoupper($state))),
                    Select::make('type')
                    ->options(['fixed' => 'Fixed', 'percent' => 'Percent'])
                    ->default('fixed')
                    ->required()->live(),
                    TextInput::make('value')->minValue(0)->prefix(fn(callable $get) => $get('type') === 'fixed' ? '$' : null)->suffix(fn(callable $get) => $get('type') === 'percent' ? '%' : null)
                    ->required()
                    ->numeric(),
                    Toggle::make('is_active')
                    ->required(),
                ]),

                Section::make('Conditions')->columns(2)->columnSpanFull()->schema([
                    TextInput::make('min_order_amount')
                        ->numeric()->minValue(0)
                        ->default(null),
                    TextInput::make('max_discount_amount')
                        ->numeric()->minValue(0)->prefix(fn(callable $get) => $get('type') === 'fixed' ? '$' : null)->suffix(fn(callable $get) => $get('type') === 'percent' ? '%' : null)
                        ->default(null),
                    TextInput::make('usage_limit')
                        ->numeric()->minValue(1)
                        ->default(null),
                    TextInput::make('usage_limit_per_user')
                        ->numeric()->minValue(1)
                        ->default(null),
                ]),

                Section::make('Validity Period')->columns(2)->columnSpanFull()->schema([
                    DateTimePicker::make('start_at')->native(false)->default(null),
                    DateTimePicker::make('end_at')->native(false)->default(null),
                ]),

            ]);
    }
}
